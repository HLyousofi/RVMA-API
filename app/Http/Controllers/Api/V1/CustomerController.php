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
        $includeInvoices = $request->query('includeInvoices');
        $includeVehicles = $request->query('includeVehicles');
        $customers = Customer::where($queryItems);
        if($includeInvoices){
            $customers = $customers->with('invoices');
        }
        if($includeVehicles){
            $customers = $customers->with('vehicles');
        }
        
        if($pageSize == 'all'){
            $customers = $customers->get();
            $selectedProperties = $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'label' => $customer->name,
                ];
            });
            
            return  $selectedProperties->toArray();
    
        }
       
        return new customerCollection($customers->paginate($pageSize)->appends($request->query()));

       

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
    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer, Request $request)
    {
        $includeInvoices = $request->query('includeInvoices');
        $includeVehicles = $request->query('includeVehicles');
        if($includeInvoices && $includeVehicles){
            $customer = $customer->loadMissing('invoices', 'vehicles');
        }
        if($includeInvoices) {
            $customer = $customer->loadMissing('invoices');
        }
        if($includeVehicles) {
            $customer = $customer->loadMissing('vehicles');
        }
        return new customerResource($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Customer $customer)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
    }
}
