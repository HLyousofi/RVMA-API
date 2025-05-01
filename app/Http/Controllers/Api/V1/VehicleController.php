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
use Illuminate\Support\Facades\Cache;


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

         // Generate a unique cache key based on query parameters
         $cacheKey = 'vehicles:' . md5(json_encode([
            'filters' => $queryItems,
            'page' => $request->query('page', 1),
            'pageSize' => $pageSize,
        ]));

        // Cache TTL: 60 minutes
        $cacheTTL = now()->addMinutes(60);

        if ($pageSize === 'all') {
            $vehicles = Cache::remember($cacheKey, $cacheTTL, function () use ($queryItems) {
                return Vehicle::where($queryItems)->get();
            });
            return VehicleResource::collection($vehicles);
        }

        // Handle paginated case
        $pageSize = $pageSize ?? 15; // Default to 15 if not provided
        $paginatedvehciles = Cache::remember($cacheKey, $cacheTTL, function () use ($vehcilesQuery, $pageSize, $request) {
           return $vehcilesQuery->paginate($pageSize)->appends($request->query()); 
        }); 

        return new VehicleCollection($paginatedvehciles);
        
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
         // Invalidate cache for this customer and customer lists
         $this->invalidateVehicleCache($vehicle->id);
         $this->invalidateVehicleListCache();
 
        $validatedData = $request->validated();
        $vehicle->update($validatedData);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
         $vehicle->delete();

         // Invalidate cache for this vehicle and customer lists
        $this->invalidateVehicleCache($VehicleId);
        $this->invalidateVehicleListCache();
    }

    /**
     * Invalidate cache for a specific customer.
     */
    private function invalidateVehicleCache($vehcileId)
    {
        // Invalidate all cache keys for this customer (with/without relationships)
        Cache::forget('vehicle:' . $vehcileId . '::');
        Cache::forget('vehicle:' . $vehcileId . ':invoices:');
        Cache::forget('vehicle:' . $vehcileId . ':vehicles:');
        Cache::forget('vehicle:' . $vehcileId . ':invoices:vehicles');
    }

    /**
     * Invalidate cache for customer lists.
     */
    private function invalidateVehicleListCache()
    {
        // Invalidate all customer list caches (simplified approach)
        // Alternatively, use a more specific approach if you have known cache keys
        Cache::tags(['vehicles'])->flush();
    }
}
