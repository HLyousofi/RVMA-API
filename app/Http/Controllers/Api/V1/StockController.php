<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Stock;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V1\StoreStockRequest;
use App\Http\Requests\V1\UpdateStockRequest;
use App\Http\Resources\V1\StockCollection;
use App\Http\Resources\V1\StockResource;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return StockResource::collection(Stock::with('product')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockRequest $request)
    {
        $stock = Stock::create($request->validated());
        return new StockResource($stock);
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        return new StockResource($stock);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockRequest $request, Stock $stock)
    {
        $stock->update($request->validated());
        return new StockResource($stock);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        $stock->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
