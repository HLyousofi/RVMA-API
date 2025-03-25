<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\QuoteProduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuoteProductController extends Controller
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
    public function store(StoreQuoteProductReQuest $request)
    {
        // Les données validées sont accessibles via $request->validated()
        $validatedData = $request->validated();

       
        // Calculate line price (quantity * unit price)
        $linePrice = $validatedData['quantity'] * $product->unit_price;
        $quoteProduct = QuoteProduct::create(array_merge($validatedData, ['line_price' => $linePrice]));
        $quoteProduct->save();

    }

    /**
     * Display the specified resource.
     */
    public function show(QuoteProduct $quoteProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(QuoteProduct $quoteProduct)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuoteProduct $quoteProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuoteProduct $quoteProduct)
    {
        //
    }
}
