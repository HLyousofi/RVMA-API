<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteProductResource extends JsonResource
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
            'quote_id' => $this->quoteId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
            'line_price' => $this->linePrice,
            // 'product' => new ProductResource($this->whenLoaded('product')),
            // 'quote' => new QuoteResource($this->whenLoaded('quote')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
