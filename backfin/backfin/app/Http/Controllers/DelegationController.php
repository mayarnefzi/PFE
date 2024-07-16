<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delegation;


class DelegationController extends Controller
{
      // Get all delegations
      public function index()
      {
          $delegations = Delegation::all();
          return response()->json($delegations);
      }
  
      // Create a new delegation
      public function store(Request $request)
      {
          $request->validate([
              'codeDel' => 'required|string|max:255',
              'libelleDel' => 'required|string|max:255',
              'region_id' => 'required|integer|exists:region,idReg',
          ]);
  
          $delegation = Delegation::create($request->all());
          return response()->json($delegation, 201);
      }
  
      // Get a delegation by ID
      public function show($id)
      {
          $delegation = Delegation::findOrFail($id);
          return response()->json($delegation);
      }
  
      // Update a delegation
      public function update(Request $request, $id)
      {
          $request->validate([
              'codeDel' => 'sometimes|required|string|max:255',
              'libelleDel' => 'sometimes|required|string|max:255',
              'region_id' => 'sometimes|required|integer|exists:region,idReg',
          ]);
  
          $delegation = Delegation::findOrFail($id);
          $delegation->update($request->all());
          return response()->json($delegation);
      }
  
      // Delete a delegation by ID
      public function destroy($id)
      {
          $delegation = Delegation::findOrFail($id);
          $delegation->delete();
          return response()->json(null, 204);
      }
}
