<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Customer;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerResource;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Controllers\Controller;
use App\Filters\V1\CustomerFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new CustomerFilter();
        $queryItems = $filter->transform($request);
        $pageSize = $request->query('pageSize');

        // Generate a unique cache key based on query parameters
        $cacheKey = 'customers:' . md5(json_encode([
            'filters' => $queryItems,
            'page' => $request->query('page', 1),
            'pageSize' => $pageSize,
        ]));

        // Cache TTL: 10 minutes
        $cacheTTL = now()->addMinutes(60);

        if ($pageSize === 'all') {
            // Cache the full customer list
            $customers = Cache::tags(['customers'])->remember($cacheKey, $cacheTTL, function () use ($queryItems) {
                return Customer::where($queryItems)->get();
            });
            return CustomerResource::collection($customers);
        }

        // Handle paginated case
        $pageSize = $pageSize ?? 10; // Default to 10 if not provided
        $paginatedCustomers = Cache::tags(['customers'])->remember($cacheKey, $cacheTTL, function () use ($queryItems, $pageSize, $request) {
            return Customer::where($queryItems)->paginate($pageSize)->appends($request->query());
        });

        return new CustomerCollection($paginatedCustomers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->all());

        // Invalidate cache for customer lists
        $this->invalidateCustomerListCache();

        

        return new CustomerResource($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer, Request $request)
    {
        $includeInvoices = $request->query('includeInvoices');
        $includeVehicles = $request->query('includeVehicles');

        // Generate a unique cache key for the customer
        $cacheKey = 'customer:' . $customer->id . ':' . ($includeInvoices ? 'invoices' : '') . ':' . ($includeVehicles ? 'vehicles' : '');

        // Cache TTL: 10 minutes
        $cacheTTL = now()->addMinutes(10);

        // Cache the customer with optional relationships
        $cachedCustomer = Cache::remember($cacheKey, $cacheTTL, function () use ($customer, $includeInvoices, $includeVehicles) {
            if ($includeInvoices && $includeVehicles) {
                return $customer->loadMissing('invoices', 'vehicles');
            }
            if ($includeInvoices) {
                return $customer->loadMissing('invoices');
            }
            if ($includeVehicles) {
                return $customer->loadMissing('vehicles');
            }
            return $customer;
        });

        return new CustomerResource($cachedCustomer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customerId = $customer->id;
        $customer->delete();
        return response()->json(null, 204);
    }

    /**
     * Invalidate cache for a specific customer.
     */
   
}