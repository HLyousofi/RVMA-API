<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use App\Http\Requests\V1\StoreProductRequest;
use App\Http\Requests\V1\UpdateProductRequest;
use App\Http\Resources\V1\ProductResource;
use App\Http\Resources\V1\ProductCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;



class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageSize = $request->query('pageSize');
        $productsQuery = Product::query();

         // Generate a unique cache key based on query parameters
         $cacheKey = 'products:' . md5(json_encode([
            'page' => $request->query('page', 1),
            'pageSize' => $pageSize,
        ]));

        // Cache TTL: 60 minutes
        $cacheTTL = now()->addMinutes(60);
        if ($pageSize === 'all') {
            $products = Cache::tags(['products'])->remember($cacheKey, $cacheTTL, function () {
                return Product::all();
            });
            return ProductResource::collection($products);
        }

        // Handle paginated case
        $pageSize = $pageSize ?? 15; // Default to 10 if not provided
        $paginatedproducts = Cache::tags(['products'])->remember($cacheKey, $cacheTTL, function () use ($productsQuery, $pageSize, $request ) {
            return $productsQuery->paginate($pageSize)->appends($request->query());
        }); 

        return new ProductCollection($paginatedproducts);
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
        return $product->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {

         return $product->delete();
    }

}
