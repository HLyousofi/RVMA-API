<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'product_id'       => 'required|exists:products,id',  // Must exist in products table
            'supplier_id'      => 'nullable|exists:suppliers,id', // Nullable but must exist if provided
            'reference_id'     => 'nullable|exists:invoices,id', // Nullable, must exist in invoices
            'transaction_type' => 'required|in:selling,purchase', // Only 'selling' or 'purchase'
            'quantity'         => 'required|integer|min:1', // Must be a positive integer
            'transaction_date' => 'required|date', // Must be a valid date
            'purchase_price'   => 'nullable|numeric|min:0', // Nullable, numeric, min 0
            'selling_price'    => 'nullable|numeric|min:0', // Nullable, numeric, min 0
            'notes'            => 'nullable|string|max:500', // Nullable, max length 500
            'location'         => 'nullable|string|max:100'
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
