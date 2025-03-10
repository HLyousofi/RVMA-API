<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Transaction;
use App\Http\Requests\V1\StoreTransactionRequest;
use App\Http\Requests\V1\UpdateTransactionRequest;
use App\Http\Resources\V1\TransactionResource;
use App\Http\Resources\V1\TransactionCollection;
use App\Services\TransactionService;
use App\Http\Controllers\Controller;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(TransactionService $transactionService) {
        $this->transactionService = $transactionService;
    }


    public function index()
    {
        return new TransactionCollection(Transaction::all());
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
    public function store(StoreTransactionRequest $request)
    {
        try {
            
            $transaction = $this->transactionService->createTransaction($request->validated());
            return new TransactionResource($transaction);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Transaction $transaction)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        try {
            $transaction = $this->transactionService->updateTransaction($transaction, $request->validated());
            return new TransactionResource($transaction);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
    }
}
