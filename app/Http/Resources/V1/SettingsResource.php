<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
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
            'companyName' => $this->company_name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'iceNumber' => $this->ice_number,
            'logoPath' => $this->logo_path ? asset('storage/' . $this->logo_path) : null,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
