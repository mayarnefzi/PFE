<?php

namespace App\Http\Controllers;
use App\Models\SiteGSM;
use App\Models\cellule4G;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Validator;

class cellule4GController extends Controller
{
    public function store(Request $request, $idSite)
    {
        $validatedData = $request->validate([
            'codeCellule' => 'required|string|max:50',
            'nomCellule' => 'required|string|max:100',
            'EnodB' => 'required|string|max:100',
            'tac' => 'required|string|max:50',
            'sc' => 'required|string|max:50',
            'power' => 'required|string|max:50',
            'mlt' => 'required|string|max:50',
            'azimuth' => 'required|string|max:50',
            'bande' => 'required|integer', // Modify the validation rules as needed
        ]);

        $validatedData['idSite'] = $idSite;

        $cellule = Cellule4G::create($validatedData);

        return response()->json(['message' => 'Cellule added successfully!', 'data' => $cellule], 201);
    }



    public function update(Request $request, $idCel)
    {
        // Find the existing Cellule4G record by its primary key
        $cellule = Cellule4G::findOrFail($idCel);
    
        // Validate incoming request data
        $validatedData = $request->validate([
            'codeCellule' => 'sometimes|nullable|string|max:50',
            'nomCellule' => 'sometimes|nullable|string|max:100',
            'EnodB' => 'sometimes|nullable|string|max:100',
            'tac' => 'sometimes|nullable|string|max:50',
            'sc' => 'sometimes|nullable|string|max:50',
            'power' => 'sometimes|nullable|string|max:50',
            'mlt' => 'sometimes|nullable|string|max:50',
            'azimuth' => 'sometimes|nullable|string|max:50',
            'bande' => 'sometimes|nullable|integer', // Modify the validation rules as needed
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
    
        // Update the existing Cellule4G record with filtered validated data
        $cellule->update($updateData);
    
        // Return a JSON response indicating success
        return response()->json(['message' => 'Cellule updated successfully!', 'data' => $cellule], 200);
    }


/////////////////////getidsite
public function getidCelBycodeCellule($codeCellule)
{
    // Fetch the site by codesite
    $cellule4G = cellule4G::where('codeCellule', $codeCellule)->first();

    // Check if the site exists
    if (!$cellule4G) {
        return response()->json(['message' => 'CELLULE not found'], 404);
    }

    // Return the idSite
    return response()->json(['idCel' => $cellule4G->idCel], 200);
}





public function showid($idCel)
{
    $cellule4G = cellule4G::where('idCel', $idCel)->get();
    return response()->json($cellule4G, 200);
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
    $cellules = Cellule4G::where('idSite', $site->idSite)->get();

    // Check if there are any cellules
    if ($cellules->isEmpty()) {
        return response()->json(['message' => 'No cellules found for this site'], 404);
    }

    // Return the cellules
    return response()->json($cellules, 200);
}

















}
