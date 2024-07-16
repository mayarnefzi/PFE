<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Secteur;

class SecteurController extends Controller
{
    // Retrieve all sectors
    public function index()
    {
        return Secteur::all();
    }




     // Create a new sector
     public function store(Request $request)
     {
         $request->validate([
             'codeSec' => 'required|string|unique:secteur',
             'libelleSec' => 'required|string',
             'delegation_id' => 'required|exists:delegation,idDel'
         ]);
 
         $secteur = Secteur::create($request->all());
 
         return response()->json($secteur, 201);
     }
}
