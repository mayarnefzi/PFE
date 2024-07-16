<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;

class RegionController extends Controller
{

      // Create a new region
      public function store(Request $request)
      {
          $request->validate([
              'libelleReg' => 'required|string|max:255',
          ]);
      
          // Vérification si le nom de région existe déjà
          $existingRegion = Region::where('libelleReg', $request->input('libelleReg'))->first();
      
          if ($existingRegion) {
              return response()->json(['error' => 'Le nom de région existe déjà.'], 400);
          }
      
          // Trouver le prochain code disponible de 1 à 24
          $usedCodes = Region::pluck('codeReg')->toArray();
      
          // Si tous les codes de 1 à 24 sont utilisés, renvoyer une erreur
          if (count($usedCodes) >= 24) {
              return response()->json(['error' => 'Tous les codes de région sont utilisés.'], 400);
          }
      
          // Recherche du prochain code disponible qui n'est pas utilisé
          for ($code = 1; $code <= 24; $code++) {
              if (!in_array($code, $usedCodes)) {
                  $nextAvailableCode = $code;
                  break;
              }
          }
      
          // Créer la nouvelle région avec le code généré
          $region = new Region();
          $region->libelleReg = $request->input('libelleReg');
          $region->codeReg = $nextAvailableCode;
          $region->save();
      
          return response()->json($region, 201);
      }
     // Show all region
      public function getall()
      {
          $regions = Region::all();
          return response()->json($regions, 200);
      }
      // Delete region by name 
      public function deleteByName($libelleReg)
      {
          $region = Region::where('libelleReg', $libelleReg)->first();
      
          if (!$region) {
              return response()->json(['error' => 'Region not found.'], 404);
          }
      
          $region->delete();
          return response()->json(['message' => 'Region deleted successfully.'], 200);
      }



     // Delete region by idReg
      public function deleteByIdReg($idReg)
      {
          $region = Region::find($idReg);
      
          if (!$region) {
              return response()->json(['error' => 'Region not found.'], 404);
          }
      
          $region->delete();
          return response()->json(['message' => 'Region deleted successfully.'], 200);
      }
      // update region by idReg
      public function update(Request $request, $idReg)
      {
          $request->validate([
              'libelleReg' => 'required|string|max:255',
          ]);
      
          $region = Region::find($idReg);
      
          if (!$region) {
              return response()->json(['error' => 'Region not found.'], 404);
          }
      
          $region->libelleReg = $request->input('libelleReg');
          $region->save();
      
          return response()->json($region, 200);
      }
      
      

}
