<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // return [
        //     // 'quote_id' => 'required|integer|exists:quotes,id',   
        //     'product_id'=> 'required|integer|exists:quotes,id',
        //     'quantity'=> 'required|numeric|min:1',
        //     'unit_price' =>'required|numeric',
        //     'line_price' =>'nullable|numeric|min:0'
        // ];
        return [
            'quoteProducts' => 'required|array|min:1', // Le tableau `quoteProducts` est requis et doit contenir au moins un élément
            'quoteProducts.*.product_id' => 'required|integer|exists:products,id', // Valide chaque `product_id` dans le tableau
            'quoteProducts.*.quantity' => 'required|numeric|min:1', // La quantité doit être un nombre >= 1
            'quoteProducts.*.unit_price' => 'required|numeric|min:0', // Le prix unitaire doit être un nombre >= 0
            // 'quoteProducts.*.line_price' => 'nullable|numeric|min:0', // Le prix total de la ligne est optionnel mais doit être >= 0
        ];
    }

    public function prepareForValidation()
{
    $quoteProducts = collect($this->quoteProducts)->map(function ($quoteProducts) {
        return [
            // 'quote_id' => $product['quoteId'] ?? $product['quote_id'],
            'product_id' => $quoteProducts['productId'] ?? $quoteProducts['product_id'],
            'unit_price' => $quoteProducts['unitPrice'] ?? $quoteProducts['unit_price'],
            'quantity' => $quoteProducts['quantity'] ?? null,
            // 'line_price' => $product['linePrice'] ?? ($product['quantity'] ?? 0) * ($product['unitPrice'] ?? 0),
        ];
    });

    $this->merge([
        'quoteProducts' => $quoteProducts->toArray(),
    ]);
}
}
