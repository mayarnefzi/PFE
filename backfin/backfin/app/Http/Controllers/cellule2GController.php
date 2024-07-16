<?php

namespace App\Http\Controllers;
use App\Models\SiteGSM;
use App\Models\cellule2G;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Validator;


class cellule2GController extends Controller
{
    public function store(Request $request, $idSite)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'codeCellule' => 'required|string|max:50',
            'nomCellule' => 'required|string|max:100',
            'lac' => 'required|string|max:50',
            'bcch' => 'required|string|max:50',
            'power' => 'required|string|max:50',
            'mlt' => 'required|string|max:50',
            'azimuth' => 'required|string|max:50',
            'bande' => 'required|integer|in:900,1800',
        ]);

        // Assign idSite to the validated data
        $validatedData['idSite'] = $idSite;

        // Create the new Cellule2G record
        $cellule = Cellule2G::create($validatedData);

        // Return a JSON response indicating success
        return response()->json(['message' => 'Cellule added successfully!', 'data' => $cellule], 201);
    }


    // public function update(Request $request, $idCel)
    // {
    //     // Find the existing Cellule2G record by its primary key
    //     $cellule = Cellule2G::findOrFail($idCel);

    //     // Validate incoming request data
    //     $validatedData = $request->validate([
    //         'codeCellule' => 'sometimes|required|string|max:50',
    //         'nomCellule' => 'sometimes|nullable|string|max:100',
    //         'lac' => 'sometimes|nullable|string|max:50',
    //         'bcch' => 'sometimes|nullable|string|max:50',
    //         'power' => 'sometimes|nullable|string|max:50',
    //         'mlt' => 'sometimes|nullable|string|max:50',
    //         'azimuth' => 'sometimes|nullable|string|max:50',
    //         'bande' => 'sometimes|nullable|integer|in:900,1800',
    //         'idSite' => 'sometimes|required|exists:site_gsm,idSite',
    //     ]);

    //     // Update the existing Cellule2G record with validated data
    //     $cellule->update($validatedData);

    //     // Return a JSON response indicating success
    //     return response()->json(['message' => 'Cellule updated successfully!', 'data' => $cellule], 200);
    // }


 
    public function update(Request $request, $idCel)
    {
        // Find the existing Cellule2G record by its primary key
        $cellule = Cellule2G::findOrFail($idCel);
    
        // Validate incoming request data
        $validatedData = $request->validate([
            'codeCellule' => 'sometimes|nullable|string|max:50',
            'nomCellule' => 'sometimes|nullable|string|max:100',
            'lac' => 'sometimes|nullable|string|max:50',
            'bcch' => 'sometimes|nullable|string|max:50',
            'power' => 'sometimes|nullable|string|max:50',
            'mlt' => 'sometimes|nullable|string|max:50',
            'azimuth' => 'sometimes|nullable|string|max:50',
            'bande' => 'sometimes|nullable|integer|in:900,1800',
            'idSite' => 'sometimes|nullable|exists:site_gsm,idSite',
        ]);
    
        // Initialize an empty array to hold fields that should be updated
        $updateData = [];
    
        // Add each field to the update data array if it is present in the request
        foreach ($validatedData as $key => $value) {
            if ($value !== null) {
                $updateData[$key] = $value;
            }
        }
    
        // Update the existing Cellule2G record with filtered validated data
        $cellule->update($updateData);
    
        // Return a JSON response indicating success
        return response()->json(['message' => 'Cellule updated successfully!', 'data' => $cellule], 200);
    }

/////////////////////getidsite
public function getidCelBycodeCellule($codeCellule)
{
    // Fetch the site by codesite
    $cellule2G = cellule2G::where('codeCellule', $codeCellule)->first();

    // Check if the site exists
    if (!$cellule2G) {
        return response()->json(['message' => 'Site not found'], 404);
    }

    // Return the idSite
    return response()->json(['idCel' => $cellule2G->idCel], 200);
}



public function showid($idCel)
{
    $cellule2G = cellule2G::where('idCel', $idCel)->get();
    return response()->json($cellule2G, 200);
}



public function showCellulesByCodeSite($codesite)
{
    // Fetch the SiteGSM by codesite
    $site = SiteGSM::where('codesite', $codesite)->first();

    // Check if the site exists
    if (!$site) {
        return response()->json(['message' => 'Site not found'], 404);
    }

    // Fetch all Cellule2G records where idSite matches the site's idSite
    $cellules = Cellule2G::where('idSite', $site->idSite)->get();

    // Check if there are any cellules
    if ($cellules->isEmpty()) {
        return response()->json(['message' => 'No cellules found for this site'], 404);
    }

    // Return the cellules
    return response()->json($cellules, 200);
}















}
