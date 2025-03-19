<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use App\Http\Requests\V1\StoreProductRequest;
use App\Http\Requests\V1\UpdateProductRequest;
use App\Http\Resources\V1\ProductResource;
use App\Http\Resources\V1\ProductCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageSize = $request->query('pageSize');
        $productsQuery = Product::query();

        if ($pageSize === 'all') {
            return ProductResource::collection($productsQuery->get());
        }

        // Handle paginated case
        $pageSize = $pageSize ?? 15; // Default to 10 if not provided
        $paginatedproducts = $productsQuery->paginate($pageSize)->appends($request->query());

        return new ProductCollection($paginatedproducts);
        // return new productCollection(Product::withSum('stocks', 'quantity')->get());
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
    public function store(StoreProductRequest $request)
    {
        // $product = Product::create($request->validated());
        // return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
        return new ProductResource(Product::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product->load('stocks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Product $product)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
       
        $product->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }
}
