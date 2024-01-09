<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Invoice;
use App\Http\Requests\V1\StoreInvoiceRequest;
use App\Http\Requests\V1\UpdateInvoiceRequest;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Resources\V1\InvoiceResource;
use App\Http\Controllers\Controller;
use App\Filters\V1\InvoiceFilter;
use Illuminate\Http\Request;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new InvoiceFilter();
        $queryItems = $filter->transform($request);
        $invoices = Invoice::where($queryItems)->get();
        // $inclideOrders = $request->query('includeOrders');
        // if($inclideOrders){
        //     $invoices = $invoices->with('orders');
        // }
        return new InvoiceCollection($invoices);
        
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
    public function store(StoreInvoiceRequest $request)
    {
        return new InvoiceResource(Invoice::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice, Request $request)
    {
        $includeOrders = $request->query('includeOrders');
        if($includeOrders){
            return new InvoiceResource($invoice->loadMissing('orders'));
        }
        return new InvoiceResource($invoice);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Invoice $invoice)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
    }
}
