<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type,
            'adress' => $this->adress,
            'email' => $this->email,
            'phoneNumber' => $this->phone_number,
            'ice' => $this->ice,
            'invoices' => InvoiceResource::collection($this->whenLoaded('invoices')),
            'vehicles' => VehicleResource::collection($this->whenLoaded('vehicles')),
        ];
    }
}
