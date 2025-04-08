<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkOrderProductRequest extends FormRequest
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
            'productsWorkOrder' => 'sometimes|array',
            'productsWorkOrder.*.product_id' => 'sometimes|integer|exists:products,id',
            'productsWorkOrder.*.quantity' => 'sometimes|integer|min:1',
            'productsWorkOrder.*.unit_price' => 'sometimes|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'productsWorkOrder.*.product_id.required' => 'The product ID is required.',
            'productsWorkOrder.*.product_id.exists' => 'The selected product does not exist.',
            'productsWorkOrder.*.quantity.min' => 'The quantity must be at least 1.',
            'productsWorkOrder.*.unit_price.numeric' => 'The price must be a number.',
            'productsWorkOrder.*.unit_price.min' => 'The price cannot be negative.',
        ];
    }

    public function prepareForValidation()
    {   
        // Get the productsQuote array from the request
        $productsWorkOrder = $this->input('productsWorkOrder', []);

        // Transform each product quote
        $transformedProductsWorkOrder = array_map(function ($item) {
            return [
                'quote_id' => $this->input('quote_id') ?? $this->route('quote') ?? null, // Optional: Add quote_id if needed
                'product_id' => $item['productId'] ?? null,
                'quantity' => $item['quantity'] ?? null,
                'unit_price' => $item['unitPrice'] ?? null, // Rename "price" to "unit_price"
            ];
        }, $productsWorkOrder);

        // Merge the transformed data back into the request
        $this->merge([
            'productsWorkOrder' => $transformedProductsWorkOrder,
        ]);
    }
}
