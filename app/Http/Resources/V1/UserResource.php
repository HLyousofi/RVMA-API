<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "id" => $this->id,
            "lastName" => $this->last_name,
            "firstName" => $this->first_name,
            "phoneNumber" => $this->phone_number,
            "email" => $this->email,
            'createdAt' => $this->created_at->format('d-m-Y H:i:s'),
            'updatedAt' => $this->updated_at->format('d-m-Y H:i:s'),
            // "createdAt" => $this->created_at,
            // "updateAt" => $this->update_at
        ];
    }
}
