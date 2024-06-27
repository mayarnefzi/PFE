<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\SiteGSM;
use App\Models\ParamArchive;
use App\Models\docfinanciere;
use App\Models\paramarchive2;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Validator;



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




public function associateArchive(Request $request, $codesite)
{
    // Find the SiteGSM by codesite
    $siteGSM = SiteGSM::where('codesite', $codesite)->first();

    if (!$siteGSM) {
        return response()->json(['message' => 'Site not found'], 404);
    }

    // Validate incoming request data
    $validatedData = $request->validate([
        'idArchive' => 'required|numeric',
        'ficheMisService' => 'required|file|mimes:pdf,doc,docx|max:2048',
        'APD' => 'required|file|mimes:pdf,doc,docx|max:2048',
        'ficheExp' => 'required|file|mimes:pdf,doc,docx|max:2048',
    ]);

    // Store uploaded files
    $ficheMisService = $request->file('ficheMisService')->store('fiche');
    $APD = $request->file('APD')->store('fiche');
    $ficheExp = $request->file('ficheExp')->store('fiche');

    // Create ParamArchive record with file paths
    $paramArchive = ParamArchive::create([
        'idArchive' => $validatedData['idArchive'],
        'ficheMisService' => $ficheMisService,
        'APD' => $APD,
        'ficheExp' => $ficheExp,
    ]);

    // Update SiteGSM with idArchive
    $this->affecterIdArchiveToSite($codesite, $validatedData['idArchive']);

    return response()->json(['message' => 'Archive associated with site successfully']);
}



  public function affecterIdArchiveToSite($codeSite, $idArchive)
    {
        // Recherche le site par codeSite
        $site = SiteGSM::where('codesite', $codeSite)->first();
    
        // Vérifie si le site existe
        if ($site) {
            // Met à jour l'ID d'archive du site
            $site->idArchive = $idArchive;
            $site->save();
    
            return response()->json(['success' => true, 'message' => 'ID d\'archive affecté avec succès au site.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Site introuvable.']);
        }
    }



/////////////////////////////////////associate finance site 

    public function associateDocFinanciere(Request $request, $codesite)
    {
        // Find the SiteGSM by codesite
        $siteGSM = SiteGSM::where('codesite', $codesite)->first();
    
        if (!$siteGSM) {
            return response()->json(['message' => 'Site not found'], 404);
        }
    
        // Validate incoming request data
        $validatedData = $request->validate([
            'contract' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Assuming contract is a file
            'propritere' => 'nullable|string',
            'montant' => 'nullable|numeric',
            'datecontract' => 'nullable|date',
            'datemaj' => 'nullable|date',
        ]);
    
        // Store uploaded contract file
        // $contract = $request->file('contract')->store('contracts');
        $contract = $request->hasFile('contract') ? $request->file('contract')->store('fiche') : null;
        // Create DocFinanciere record with contract file path
        $docFinanciere = DocFinanciere::create([
            'contract' => $contract,
            'propritere' => $validatedData['propritere'],
            'montant' => $validatedData['montant'],
            'datecontract' => $validatedData['datecontract'],
            'datemaj' => $validatedData['datemaj'],
        ]);
    
        // Update SiteGSM with iddocfin
        $siteGSM->iddocfin = $docFinanciere->iddocfin;
        $siteGSM->save();
    
        return response()->json(['message' => 'DocFinanciere associated with site successfully']);
    }
    
    // Helper function to update SiteGSM with iddocfin
    private function affecterIdDocFinToSite($codesite, $iddocfin)
    {
        $siteGSM = SiteGSM::where('codesite', $codesite)->first();
        if ($siteGSM) {
            $siteGSM->iddocfin = $iddocfin;
            $siteGSM->save();
            return response()->json(['success' => true, 'message' => 'ID d\'docfinanciere affecté avec succès au site.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Site introuvable.']);
        }
    }
///////////////////////////////// finance edit function 

// public function updateDocFinanciere(Request $request, $codesite)
// {
//     // Find the SiteGSM by codesite
//     $siteGSM = SiteGSM::where('codesite', $codesite)->first();

//     if (!$siteGSM) {
//         return response()->json(['message' => 'Site not found'], 404);
//     }

//     // Find the associated DocFinanciere by iddocfin
//     $docFinanciere = DocFinanciere::find($siteGSM->iddocfin);

//     if (!$docFinanciere) {
//         return response()->json(['message' => 'DocFinanciere not found'], 404);
//     }

//     // Update contract file if provided
//     if ($request->hasFile('contract')) {
//         $contract = $request->file('contract')->store('contracts');
//         $docFinanciere->contract = $contract;
        
//     }

//     // Update other fields if provided
//     if ($request->filled('propritere')) {
//         $docFinanciere->propritere = $request->input('propritere');
//     }
//     if ($request->filled('montant')) {
//         $docFinanciere->montant = $request->input('montant');
//     }
//     if ($request->filled('datecontract')) {
//         $docFinanciere->datecontract = $request->input('datecontract');
//     }
//     if ($request->filled('datemaj')) {
//         $docFinanciere->datemaj = $request->input('datemaj');
//     }

//     // Save the updated DocFinanciere
//     $docFinanciere->save();

//     // Return the updated DocFinanciere with iddocfin and all columns
//     return response()->json([
//         'message' => 'DocFinanciere updated successfully',
//         'docFinanciere' => $docFinanciere
//     ]);
// }

// public function updateDocFinanciere(Request $request, $codesite)
// {
//     // Find the SiteGSM by codesite
//     $siteGSM = SiteGSM::where('codesite', $codesite)->first();

//     if (!$siteGSM) {
//         return response()->json(['message' => 'Site not found'], 404);
//     }

//     // Find the associated DocFinanciere by iddocfin
//     $docFinanciere = DocFinanciere::find($siteGSM->iddocfin);

//     if (!$docFinanciere) {
//         return response()->json(['message' => 'DocFinanciere not found'], 404);
//     }

//     // Get the current values
//     $currentValues = $docFinanciere->toArray();

//     // Update contract file if provided
//     if ($request->hasFile('contract')) {
//         $contract = $request->file('contract')->store('contracts');
//         $docFinanciere->contract = $contract;
//     }

//     // Update other fields if provided
//     foreach ($request->all() as $key => $value) {
//         if (array_key_exists($key, $currentValues) && $value !== $currentValues[$key]) {
//             $docFinanciere->$key = $value;
//         }
//     }

//     // Save the updated DocFinanciere
//     $docFinanciere->save();

//     // Return the updated DocFinanciere with iddocfin and all columns
//     return response()->json([
//         'message' => 'DocFinanciere updated successfully',
//         'docFinanciere' => $docFinanciere
//     ]);
// }

// public function updateDocFinanciere(Request $request, $iddocfin)
// {
//     // Validate incoming request data for the resource update
//     $validator = Validator::make($request->all(), [
//         'propritere' => 'required|string',
//         'montant' => 'required|numeric',
//         'datecontract' => 'required|date',
//         'datemaj' => 'required|date',
//     ]);

//     // Check if validation fails
//     if ($validator->fails()) {
//         return response()->json(['error' => $validator->errors()], 400);
//     }

//     // Find the DocFinanciere record by iddocfin
//     $docFin = DocFinanciere::find($iddocfin);

//     // Check if the record exists
//     if (!$docFin) {
//         return response()->json(['error' => 'DocFinanciere not found'], 404);
//     }

//     // Update other fields with request data
//     $docFin->propritere = $request->input('propritere');
//     $docFin->montant = $request->input('montant');
//     $docFin->datecontract = $request->input('datecontract');
//     $docFin->datemaj = $request->input('datemaj');

//     // Save the updated record
//     $docFin->save();

//     // Log the update
//     Log::info('DocFinanciere updated: ' . $iddocfin);

//     // Return success response
//     return response()->json(['message' => 'DocFinanciere updated successfully'], 200);
// }


///////////////////////////////archive2avec idauto
public function associateparamarchive2(Request $request, $codesite)
{
    // Find the SiteGSM by codesite
    $siteGSM = SiteGSM::where('codesite', $codesite)->first();

    if (!$siteGSM) {
        return response()->json(['message' => 'Site not found'], 404);
    }

    // Validate incoming request data
    $validatedData = $request->validate([
        'ficheMisService' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        'APD' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        'ficheExp' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
    ]);

    // Store uploaded files if they exist, otherwise set to null
    $ficheMisService = $request->hasFile('ficheMisService') ? $request->file('ficheMisService')->store('fiche') : null;
    $APD = $request->hasFile('APD') ? $request->file('APD')->store('fiche') : null;
    $ficheExp = $request->hasFile('ficheExp') ? $request->file('ficheExp')->store('fiche') : null;

    // Create ParamArchive2 record with file paths
    $paramarchive2 = Paramarchive2::create([
        'ficheMisService' => $ficheMisService,
        'APD' => $APD,
        'ficheExp' => $ficheExp,
    ]);

    // Update SiteGSM with idArchive2
    $siteGSM->idArchive2 = $paramarchive2->idArchive2;
    $siteGSM->save();

    return response()->json(['message' => 'paramarchive associated with site successfully']);
}


    
//////////////////////get sites by delegation

    public function getSitesByRegion(Request $request)
{
    $region = $request->input('region');
    $sites = SiteGSM::where('region', $region)->get();
    return response()->json($sites, 200);
}
    
//////////////////////////////take region and take the delegation the get sitegsm 
public function getIdDocFinByCodeSite($codesite)
{
    // Fetch the site by codesite
    $siteGSM = SiteGSM::where('codesite', $codesite)->first();

    // Check if the site exists
    if (!$siteGSM) {
        return response()->json(['message' => 'Site not found'], 404);
    }

    // Return the iddocfin
    return response()->json(['iddocfin' => $siteGSM->iddocfin], 200);
}
public function getSitesByRegionAndDelegotion(Request $request)
{
    $region = $request->input('region');
    $delegotion = $request->input('delegotion');

    // Check if both region and delegation are provided
    if (!$region || !$delegotion) {
        return response()->json(['message' => 'Both region and delegotion are required'], 400);
    }

    // Query sites based on region and delegation names
    $sites = SiteGSM::where('region', $region)
                    ->where('delegotion', $delegotion)
                    ->get();

    return response()->json($sites, 200);
}

/////////////////////TB SITE

public function getArchiveByCodeSite($codesite)
{
    // Fetch the site by codesite
    $siteGSM = SiteGSM::where('codesite', $codesite)->first();

    // Check if the site exists
    if (!$siteGSM) {
        return response()->json(['message' => 'Site not found'], 404);
    }

    // Fetch the document from the docfinanciere table using the iddocfin
    $paramarchive2 = paramarchive2::where('idArchive2', $siteGSM->idArchive2)->first();

    // Check if the document exists
    if (!$paramarchive2) {
        return response()->json(['message' => 'Document not found'], 404);
    }

    // Return the document data
    return response()->json($paramarchive2, 200);
}

public function getDocFinByCodeSite($codesite)
{
    // Fetch the site by codesite
    $siteGSM = SiteGSM::where('codesite', $codesite)->first();

    // Check if the site exists
    if (!$siteGSM) {
        return response()->json(['message' => 'Site not found'], 404);
    }

    // Fetch the document from the docfinanciere table using the iddocfin
    $docFinanciere = docfinanciere::where('iddocfin', $siteGSM->iddocfin)->first();

    // Check if the document exists
    if (!$docFinanciere) {
        return response()->json(['message' => 'Document not found'], 404);
    }

    // Return the document data
    return response()->json($docFinanciere, 200);
}




public function getfournisseurByRegionAndDelegotion(Request $request)
{
    $region = $request->input('region');
    $delegotion = $request->input('delegotion');

    // Check if both region and delegation are provided
    if (!$region || !$delegotion) {
        return response()->json(['message' => 'Both region and delegotion are required'], 400);
    }

    // Query sites based on region and delegation names
    // Requête des fournisseurs en fonction des noms de région et de délégation
    $fournisseurs = SiteGSM::where('region', $region)
                    ->where('delegotion', $delegotion)
                    ->distinct()
                    ->pluck('fournisseur');

    return response()->json($fournisseurs, 200);
}

public function getsitesByRegionAndDelegotionAndFournisseur(Request $request)
{
    $region = $request->input('region');
    $delegotion = $request->input('delegotion');
    $fournisseur = $request->input('fournisseur');
    // Check if both region and delegation are provided
    if (!$region || !$delegotion ||!$fournisseur ) {
        return response()->json(['message' => 'Both region and delegotion are required'], 400);
    }

    // Query sites based on region and delegation names
    // Requête des fournisseurs en fonction des noms de région et de délégation
    $sites  = SiteGSM::where('region', $region)
                    ->where('delegotion', $delegotion)
                    ->where('fournisseur',$fournisseur)
                    ->get();

    return response()->json($sites , 200);
}



/////////////////////getidsite
public function getIdSiteByCodeSite($codesite)
{
    // Fetch the site by codesite
    $siteGSM = SiteGSM::where('codesite', $codesite)->first();

    // Check if the site exists
    if (!$siteGSM) {
        return response()->json(['message' => 'Site not found'], 404);
    }

    // Return the idSite
    return response()->json(['idSite' => $siteGSM->idSite], 200);
}



    
}
