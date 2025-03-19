<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        $base = [
            'id' => $this->id,
            'label' => $this->name,
        ];

        // Add extra fields only if not for autocomplete
        if ($request->query('pageSize') !== 'all') {
            $base = array_merge($base, [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand,
            'model' => $this->model,
            'oemReference' => $this->oem_reference,
            'manufacturerReference' => $this->manufacturer_reference,
            'description' => $this->description,
            'referance' => $this->referance,
            'purchasePrice' => $this->purchase_price,
            'sellingPrice' => $this->selling_price,
            'totalStock' => $this->stocks->sum('quantity'),
                
            ]);
        }

        return $base;
        // return [
        //     'id' => $this->id,
        //     'name' => $this->name,
        //     'brand' => $this->brand,
        //     'model' => $this->model,
        //     'oemReference' => $this->oem_reference,
        //     'manufacturerReference' => $this->manufacturer_reference,
        //     'description' => $this->description,
        //     'referance' => $this->referance,
        //     'purchasePrice' => $this->purchase_price,
        //     'sellingPrice' => $this->selling_price,
        //     'totalStock' => $this->stocks->sum('quantity'),
        //     'category' => new CategoryResource($this->category),
        //     // 'stock' => new StockResource($this->stock)
            
        // ];
    }
}
