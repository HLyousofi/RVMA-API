<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FuelType;
use App\Http\Resources\V1\FuelTypeResource;
use App\Http\Resources\V1\FuelTypeCollection;


class FuelTypeController extends Controller
{
    //
     // Get a list of all fuel types
     public function index()
     {
         $fuelTypes = FuelType::all();
         return new FuelTypeCollection($fuelTypes);
     }
 
     // Show a specific fuel type
     public function show($id)
     {
         $fuelType = FuelType::findOrFail($id);
         return response()->json($fuelType);
     }
 
     // Store a new fuel type
     public function store(Request $request)
     {
         $request->validate([
             'name' => 'required|string|max:255',
         ]);
 
         $fuelType = FuelType::create($request->all());
         return response()->json($fuelType, 201);
     }
 
     // Update an existing fuel type
     public function update(Request $request, $id)
     {
         $fuelType = FuelType::findOrFail($id);
 
         $request->validate([
             'name' => 'required|string|max:255',
         ]);
 
         $fuelType->update($request->all());
         return response()->json($fuelType);
     }
 
     // Delete a fuel type
     public function destroy($id)
     {
         $fuelType = FuelType::findOrFail($id);
         $fuelType->delete();
         return response()->json(['message' => 'Fuel type deleted successfully']);
     }
}
