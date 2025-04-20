<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workorderId' => $this->workorder_id,
            'invoiceNumber' => $this->invoice_number,
            'amount' => $this->amount,
            'status' => $this->status,
            'billedDate' => $this->billed_date,
            'paidDate' => $this->paid_date,
            'customer' => new CustomerResource($this->customer),
            'vehicle' => new VehicleResource($this->vehicle),
            'discount' => $this->discount,
            'products' => $this->whenLoaded('products', function () {
                return $this->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name, // Remplacez par le champ réel de votre modèle Product
                        'quantity' => $product->pivot->quantity,
                        'unitPrice' => $product->pivot->unit_price,
                        'total' => $product->pivot->quantity * $product->pivot->unit_price,
                    ];
                });
            }),
        ];
    }
}
