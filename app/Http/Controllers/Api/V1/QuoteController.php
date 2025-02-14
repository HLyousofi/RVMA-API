<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Quote;
use App\Http\Requests\V1\StoreQuotesRequest;
use App\Http\Requests\V1\UpdateQuotesRequest;
use App\Http\Requests\V1\StoreQuoteProductRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\QuoteCollection;
use App\Http\Resources\V1\QuoteResource;
use App\Filters\V1\QuoteFilter;
use Illuminate\Http\Request;
use App\services\QuoteService;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function index(Request $request)
    {
        $filter = new QuoteFilter();
        $queryItems = $filter->transform($request);
        $quotes = Quote::where($queryItems);
        $includeOrders = $request->query('includeOrders');
        if($includeOrders){
            $quotes = $quotes->with('orders');
        }
         
        return new QuoteCollection($quotes->paginate()->appends($request->query()));
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
    public function store(StoreQuotesRequest $quoteRequest, StoreQuoteProductRequest $productRequest)
    {     
         // Récupérer les données validées
        $quoteData = $quoteRequest->validated();
        $productData = $productRequest->validated();
        try {
            // Créer le devis en utilisant le service
            $quote = $this->quoteService->createQuote($quoteData, $productData['quoteProducts']);

            return response()->json(['quote' => $quote], 201);
        } catch (\Exception $e) {
            // Gestion d'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote,Request $request)
    {
        $includeOrders = $request->query('includeOrders');
        if( $includeOrders){
            return new QuoteResource($quote->loadMissing('orders'));
        }
        return new quoteResource($quote);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Quotes $quotes)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuotesRequest $request, Quote $quote)
    {
        $quote->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotes $quotes)
    {
        //
    }
}
