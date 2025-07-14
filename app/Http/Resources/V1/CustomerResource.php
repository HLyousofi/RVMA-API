<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\ContactResource;


class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $base = [
            'id' => $this->id,
            'label' => $this->name,
        ];

        // Add extra fields only if not for autocomplete
        if ($request->query('pageSize') !== 'all') {
            $base = array_merge($base, [
                'id' => $this->id,
                'name' => $this->name,
                'type' => $this->type,
                'adress' => $this->adress,
                'email' => $this->email,
                'phoneNumber' => $this->phone_number,
                'ice' => $this->ice,
                'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            ]);
        }

        return $base;
        // return [
        //     'id' => $this->id,
        //     'name' => $this->name,
        //     'type' => $this->type,
        //     'adress' => $this->adress,
        //     'email' => $this->email,
        //     'phoneNumber' => $this->phone_number,
        //     'ice' => $this->ice,
        //     // 'invoices' => InvoiceResource::collection($this->whenLoaded('invoices')),
        //     // 'vehicles' => VehicleResource::collection($this->whenLoaded('vehicles')),
        // ];
    }
}
