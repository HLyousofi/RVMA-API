<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'productId' => $this->product_id,
            'supplierId' => $this->supplier_id,
            'referenceId' => $this->reference_id,
            'transactionType' => $this->transaction_type,
            'quantity' => $this->quantity,
            'transactionDate' => $this->transaction_date,
            'purchasePrice' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'notes' => $this->notes
           
        ];
    }
}
