<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $vehicleBrand  = isset($this->vehicle->brand) ?$this->vehicle->brand : 'supprimer';
        $vehiclePlatNumber  = isset($this->vehicle->plate_number) ?$this->vehicle->plate_number : 'supprimer';

     
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'task' => $this->task,
            'status' => $this->status,
            'vehicle' => $vehicleBrand,
            'plateNumber'=> $vehiclePlatNumber
        
        ];
    }
}
