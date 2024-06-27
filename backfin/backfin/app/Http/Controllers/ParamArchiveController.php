<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParamArchive;


class ParamArchiveController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'idArchive' => 'required|numeric',
            'ficheMisService' => 'required|file|mimes:pdf,doc,docx|max:2048', // Example validation for PDF, DOC, DOCX files up to 2MB
            'APD' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'ficheExp' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Store uploaded files
        $ficheMisService = $request->file('ficheMisService')->store('fiche');
        $APD = $request->file('APD')->store('fiche');
        $ficheExp = $request->file('ficheExp')->store('fiche');

        // Create ParamArchive record with file paths
        $paramArchive = ParamArchive::create([
            'ficheMisService' => $ficheMisService,
            'APD' => $APD,
            'ficheExp' => $ficheExp,
        ]);

        return response()->json(['message' => 'ParamArchive created', 'data' => $paramArchive], 201);
    }



}
