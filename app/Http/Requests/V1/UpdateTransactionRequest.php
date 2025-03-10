<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'product_id'       => 'sometimes|exists:products,id',  // Can be updated if provided
            'supplier_id'      => 'nullable|exists:suppliers,id', // Ensure supplier exists
            'reference_id'     => 'nullable|exists:invoices,id', // Must exist if provided
            'transaction_type' => 'sometimes|in:selling,purchase', // Only 'selling' or 'purchase'
            'quantity'         => 'sometimes|integer|min:1', // Must be positive integer
            'transaction_date' => 'sometimes|date', // Valid date format
            'purchase_price'   => 'nullable|numeric|min:0', // Numeric, minimum 0
            'selling_price'    => 'nullable|numeric|min:0', // Numeric, minimum 0
            'notes'            => 'nullable|string|max:500', // Max 500 characters
        ];
    }

    public function prepareForValidation(){
        return $this->merge([
            'product_id' => $this->productId,
            'supplier_id' => $this->supplierId,
            'reference_id' => $this->referenceId,
            'transaction_type' => $this->transactionType,
            'transaction_date' => $this->transactionDate,
            'purchase_price' => $this->purchasePrice,
            'selling_price' => $this->sellingPrice
        ]);
    }
}
