<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteProductRequest extends FormRequest
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
        return [
            'productsQuote' => 'sometimes|array',
            'productsQuote.*.productId' => 'required|integer|exists:products,id',
            'productsQuote.*.quantity' => 'sometimes|integer|min:1',
            'productsQuote.*.price' => 'sometimes|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'productsQuote.*.productId.required' => 'The product ID is required.',
            'productsQuote.*.productId.exists' => 'The selected product does not exist.',
            'productsQuote.*.quantity.min' => 'The quantity must be at least 1.',
            'productsQuote.*.price.numeric' => 'The price must be a number.',
            'productsQuote.*.price.min' => 'The price cannot be negative.',
        ];
    }

    public function prepareForValidation()
    {   
        // Get the productsQuote array from the request
        $productsQuote = $this->input('productsQuote', []);

        // Transform each product quote
        $transformedproductsQuote = array_map(function ($item) {
            return [
                'quote_id' => $this->input('quote_id') ?? $this->route('quote') ?? null, // Optional: Add quote_id if needed
                'product_id' => $item['productId'] ?? null,
                'quantity' => $item['quantity'] ?? null,
                'unit_price' => $item['price'] ?? null, // Rename "price" to "unit_price"
            ];
        }, $productsQuote);

        // Merge the transformed data back into the request
        $this->merge([
            'productsQuote' => $transformedproductsQuote,
        ]);
    }
}
