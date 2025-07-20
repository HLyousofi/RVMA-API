<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'productsInvoice' => 'required|array',
            'productsInvoice.*.product_id' => 'required|integer|exists:products,id',
            'productsInvoice.*.quantity' => 'required|integer|min:1',
            'productsInvoice.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'productsInvoice.required' => 'At least one product is required.',
            'productsInvoice.*.product_id.required' => 'The product ID is required.',
            'productsInvoice.*.product_id.exists' => 'The selected product does not exist.',
            'productsInvoice.*.quantity.required' => 'The quantity is required.',
            'productsInvoice.*.quantity.min' => 'The quantity must be at least 1.',
            'productsInvoice.*.unit_price.required' => 'The price is required.',
            'productsInvoice.*.unit_price.numeric' => 'The price must be a number.',
            'productsInvoice.*.unit_price.min' => 'The price cannot be negative.',
        ];
    }

    public function prepareForValidation()
    {
        // Get the productsWorkOrder array from the request
        $productsWorkOrder = $this->input('productsInvoice', []);
    
        // Transform each product quote
        $transformedproductsWorkOrder = array_map(function ($item) {
            return [
                'product_id' => $item['productId'] ?? null,
                'quantity' => $item['quantity'] ?? null,
                'unit_price' => $item['unitPrice'] ?? null, // Rename "price" to "unit_price"
            ];
        }, $productsInvoice);
    
        // Merge the transformed data back into the request
        $this->merge([
            'productsInvoice' => $transformedproductsInvoice,
        ]);
    }
}
