<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $customerName = isset($this->customer->name) ?$this->customer->name : 'supprimer';
        $customerId = isset($this->customer->id) ? $this->customer->id : 0;
        // $fuelType = isset($this->fuelType->fuel_type) ? $this->fuelType->fuel_type : 0;

        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'model' => $this->model,
            'plateNumber' => $this->plate_number,
            'fuelTypeId' => $this->fuelType->id,
            'fuelType' => $this->fuelType->fuel_type,
            'customerName' => $customerName,
            'customerId' => $customerId,
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'quotes' => QuoteResource::collection($this->whenLoaded('quotes'))
            
        ];
    }
}
