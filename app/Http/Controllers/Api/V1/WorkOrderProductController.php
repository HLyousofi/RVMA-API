<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\WorkOrderProduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkOrderProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreWorkOrderProductReQuest $request)
    {
        // Les données validées sont accessibles via $request->validated()
        $validatedData = $request->validated();

       
        // Calculate line price (quantity * unit price)
        $linePrice = $validatedData['quantity'] * $product->unit_price;
        $quoteProduct = WorkOrderProduct::create(array_merge($validatedData, ['line_price' => $linePrice]));
        $quoteProduct->save();

    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrderProduct $quoteProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(WorkOrderProduct $quoteProduct)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrderProduct $quoteProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrderProduct $quoteProduct)
    {
        //
    }
}
