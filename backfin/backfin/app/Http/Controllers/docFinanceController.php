<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use App\Models\DocFinanciere;
use Illuminate\Support\Facades\Validator;

class docFinanceController extends Controller
{
    public function uploadContract(Request $request, $iddocfin)
    {
        // Validate incoming request data for file upload
        $validator = Validator::make($request->all(), [
            'contract' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        // Find the DocFinanciere record by iddocfin
        $docFin = DocFinanciere::find($iddocfin);
    
        // Check if the record exists
        if (!$docFin) {
            return response()->json(['error' => 'DocFinanciere not found'], 404);
        }
    
        try {
            // Handle file upload
            if ($request->hasFile('contract')) {
                $file = $request->file('contract');
                $originalName = $file->getClientOriginalName();
                // $contractPath = $file->store('contracts');
                $contractPath = $file->storeAs('contracts', $originalName, 'public');
                // Update the contract path in the DocFinanciere record
                $docFin->contract = $contractPath;
                $docFin->save();
    
                // Log the file upload
                Log::info('Contract uploaded for DocFinanciere: ' . $iddocfin);
    
                // Return success response
                return response()->json(['message' => 'Contract uploaded successfully'], 200);
            } else {
                // Return a message indicating no file was provided for upload
                return response()->json(['message' => 'No contract file provided'], 400);
            }
        } catch (\Exception $e) {
            // Handle any unexpected exceptions
            Log::error('Error uploading contract for DocFinanciere ' . $iddocfin . ': ' . $e->getMessage());
            return response()->json(['error' => 'Failed to upload contract'], 500);
        }
    }


    public function updateDocFinanciere(Request $request, $iddocfin)
{
    // Validate incoming request data for the resource update
    $validator = Validator::make($request->all(), [
        'propritere' => 'nullable|string',
        'montant' => 'nullable|numeric',
        'datecontract' => 'nullable|date',
        'datemaj' => 'nullable|date',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Find the DocFinanciere record by iddocfin
    $docFin = DocFinanciere::find($iddocfin);

    // Check if the record exists
    if (!$docFin) {
        return response()->json(['error' => 'DocFinanciere not found'], 404);
    }

    // Update fields with request data if provided
    if ($request->filled('propritere')) {
        $docFin->propritere = $request->input('propritere');
    }

    if ($request->filled('montant')) {
        $docFin->montant = $request->input('montant');
    }

    if ($request->filled('datecontract')) {
        $docFin->datecontract = $request->input('datecontract');
    }

    if ($request->filled('datemaj')) {
        $docFin->datemaj = $request->input('datemaj');
    }

  
        // Save the updated record if any field was updated
        if ($docFin->isDirty()) {
            $docFin->save();
           
            // Log the update
            Log::info('DocFinanciere updated: ' . $iddocfin);
    
            // Return success response
            return response()->json(['message' => 'DocFinanciere updated successfully'], 200);
        }


}

















    
    
    
}
