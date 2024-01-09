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

        $customerName = isset($this->customer->name) ? $this->customer->name : 'supprime';
        return [
            'id' => $this->id,
            'customerName' => $customerName,
            'amount' => $this->amount,
            'status' => $this->status,
            'billedDate' => $this->billed_date,
            'paidDate' => $this->paid_date,
            'orders' => OrderResource::collection($this->whenLoaded('orders'))

        ];
    }
}
