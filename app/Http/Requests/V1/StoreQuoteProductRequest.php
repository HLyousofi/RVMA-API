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
        return [
            'quote_id' => 'required|integer|exists:quotes,id',   
            'product_id'=> 'required|integer|exists:quotes,id',
            'quantity'=> 'required|numeric|min:1',
            'unit_price' =>'required|numeric',
            'line_price' =>'nullable|numeric|min:0'
        ];
    }

    public function prepareForValidation(){
        return $this->merge([
            'quote_id' => $this->quoteId,
            'product_id' => $this->productId,
            'unit_price' => $this->unitPrice,

            // 'line_price' => $this->linePrice
        ]);
    }
}
