<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteGSM;

class SiteGSMController extends Controller
{
    public function storesite(Request $request)
    {
        $validatedData = $request->validate([
            'codesite' => 'required|string|unique:SiteGSM',
            'nomsite' => 'required|string',
            'region' => 'required|string',
            'delegotion' => 'required|string',
            'secteur' => 'required|string',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'fournisseur' => 'required|string',
            'HBA' => 'nullable|string',
            'antenne' => 'nullable|string',
            'alimentation' => 'nullable|string',
            'acces' => 'nullable|string',
        ]);
    
        $site = SiteGSM::create($validatedData);
    
        if ($site) {
            return response()->json(['message' => 'Site créé avec succès', 'code_site' => $site->codesite], 201);
        } else {
            return response()->json(['message' => 'Erreur lors de la création du site'], 500);
        }
    }
    

    public function show()
    {
        $sites = SiteGSM::all();
        return response()->json($sites, 200);
    }



    public function showid($codesite)
    {
        $sites = SiteGSM::where('codesite', $codesite)->get();
        return response()->json($sites, 200);
    }


    public function destroysite($codesite)
    {
        $sites = SiteGSM::where('codesite', $codesite)->get();
    
        if ($sites->isEmpty()) {
            return response()->json(['message' => 'Aucun site avec ce code n\'a été trouvé'], 404);
        }
    
        foreach ($sites as $site) {
            $site->delete();
        }
    
        return response()->json(['message' => 'Sites supprimés avec succès'], 200);
    }
    
  
    public function updatesite(Request $request, $codesite)
{
    // Récupérer le site à mettre à jour
    $site = SiteGSM::where('codesite', $codesite)->firstOrFail();
    
    // Valider les données de la requête
    $validatedData = $request->validate([
        'codesite' => 'required|string',
        'nomsite' => 'required|string',
        'region' => 'required|string',
        'delegotion' => 'required|string',
        'secteur' => 'required|string',
        'x' => 'required|numeric',
        'y' => 'required|numeric',
        'fournisseur' => 'required|string',
        'HBA' => 'nullable|string',
        'antenne' => 'nullable|string',
        'alimentation' => 'nullable|string',
        'acces' => 'nullable|string',
    ]);

    // Mettre à jour les données du site
    $site->update($validatedData);
    
    // Retourner une réponse JSON avec un message de succès
    return response()->json(['message' => 'Site mis à jour avec succès'], 200);
}





    
}
