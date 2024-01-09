<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Supplier;
use App\Http\Requests\V1\StoreSupplierRequest;
use App\Http\Requests\V1\UpdateSupplierRequest;
use App\Http\Resources\V1\SupplierResource;
use App\Http\Resources\V1\SupplierCollection;
use App\Http\Controllers\Controller;


class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new SupllierCollection(Supplier::all());
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
    public function store(StoreSupplierRequest $request)
    {
        return new SupllierResource(Supplier::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return new SupplierResource($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Supplier $supplier)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supllier->delete();
    }
}
