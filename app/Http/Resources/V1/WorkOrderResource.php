<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\VehicleResource;


class WorkOrderResource extends JsonResource
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
            'workorderNumber' => $this->workorder_number,
            'vehicle' => new VehicleResource($this->vehicle),
            'type' => $this->type, 
            'customer' => new CustomerResource($this->customer),
            'status' => $this->status,
            'expirationDate' => $this->expiration_date,
            'total' => $this->total,
            //'invoice_id' => $this->invoice_id,
            'orderDate' => $this->order_date,
            'deliveryDate' => $this->delivery_date,
            'comment' => $this->comment,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
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
