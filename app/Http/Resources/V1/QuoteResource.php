<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\VehicleResource;


class QuoteResource extends JsonResource
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
            'vehicle'=> new VehicleResource($this->vehicle),
            'creationDate' => $this->creation_date,
            'expirationDate' => $this->expiration_date,
            'status' => $this->status,
            'comment' => $this->comment,
        ];
    }
}
