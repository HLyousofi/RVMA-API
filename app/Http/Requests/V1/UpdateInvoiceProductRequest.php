<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceProductRequest extends FormRequest
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
            'productsInvoice' => 'sometimes|array',
            'productsInvoice.*.product_id' => 'sometimes|integer|exists:products,id',
            'productsInvoice.*.quantity' => 'sometimes|integer|min:1',
            'productsInvoice.*.unit_price' => 'sometimes|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'productsInvoice.*.product_id.required' => 'The product ID is required.',
            'productsInvoice.*.product_id.exists' => 'The selected product does not exist.',
            'productsInvoice.*.quantity.min' => 'The quantity must be at least 1.',
            'productsInvoice.*.unit_price.numeric' => 'The price must be a number.',
            'productsInvoice.*.unit_price.min' => 'The price cannot be negative.',
        ];
    }

    public function prepareForValidation()
    {   
        // Get the productsQuote array from the request
        $productsInvoice = $this->input('productsInvoice', []);

        // Transform each product quote
        $transformedProductsInvoice = array_map(function ($item) {
            return [
                'product_id' => $item['productId'] ?? null,
                'quantity' => $item['quantity'] ?? null,
                'unit_price' => $item['unitPrice'] ?? null, // Rename "price" to "unit_price"
            ];
        }, $productsInvoice);

        // Merge the transformed data back into the request
        $this->merge([
            'productsInvoice' => $transformedProductsInvoice,
        ]);
    }
}
