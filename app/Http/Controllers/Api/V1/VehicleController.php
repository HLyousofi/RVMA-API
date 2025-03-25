<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Vehicle;
use App\Http\Requests\V1\StoreVehicleRequest;
use App\Http\Requests\V1\UpdateVehicleRequest;
use App\Http\Controllers\Controller;
use App\Filters\V1\VehicleFilter;
use App\Http\Resources\V1\VehicleCollection;
use App\Http\Resources\V1\VehicleResource;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new VehicleFilter();
        $queryItems = $filter->transform($request);
        $pageSize = $request->query('pageSize');
        // $vehicles = Vehicle::where($queryItems);
        $vehcilesQuery = Vehicle::query();
        if ($pageSize === 'all') {
            return VehicleResource::collection($vehcilesQuery->get());
        }

        // Handle paginated case
        $pageSize = $pageSize ?? 15; // Default to 10 if not provided
        $paginatedvehciles = $vehcilesQuery->paginate($pageSize)->appends($request->query());

        return new VehicleCollection($paginatedvehciles);
        
       // $vehicles = Vehicle::where($queryItems);
        // return new VehicleCollection($vehicles->paginate($pageSize)->appends($request->query()));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        $validatedData = $request->validated();

        return new VehicleResource(Vehicle::create($validatedData));
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle, Request $request)
    {
        $includeOrders = $request->query('includeOrders');
        if($includeOrders){
            return new VehicleResource($vehicle->loadMissing('orders'));
        }
        return new VehicleResource($vehicle);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Vehicle $vehicle)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $validatedData = $request->validated();
        $vehicle->update($validatedData);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
         $vehicle->delete();
    }
}
