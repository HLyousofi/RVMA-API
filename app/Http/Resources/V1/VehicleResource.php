<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\CarBrandResource;
use App\Http\Resources\V1\FuelTypeResource;



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

        $base = [
            'id' => $this->id,
            'label' => $this->plate_number,
            'customerId' => $customerId
        ];

        // Add extra fields only if not for autocomplete
        if ($request->query('pageSize') !== 'all') {
            $base = [
            'id' => $this->id,
            'brand' => new CarBrandResource($this->brand),
            'model' => $this->model,
            'plateNumber' => $this->plate_number,
            'fuelType' => new FuelTypeResource($this->fuelType),
            'customerName' => $customerName,
            'customerId' => $customerId,
                
            ];
        }
        return $base;
    }
    // public function toArray(Request $request): array
    // {
    //     // return parent::toArray($request);
    //     $customerName = isset($this->customer->name) ?$this->customer->name : 'supprimer';
    //     $customerId = isset($this->customer->id) ? $this->customer->id : 0;

    //     return [
    //         'id' => $this->id,
    //         'brand' => new CarBrandResource($this->brand),
    //         'model' => $this->model,
    //         'plateNumber' => $this->plate_number,
    //         'fuelType' => new FuelTypeResource($this->fuelType),
    //         'customerName' => $customerName,
    //         'customerId' => $customerId,
    //         // 'orders' => OrderResource::collection($this->whenLoaded('orders')),
    //         // 'quotes' => QuoteResource::collection($this->whenLoaded('quotes'))
            
    //     ];

        
    // }
}
