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
        $method = $this->method();
        if($method == 'PUT'){
            return [
                'quote_id' => 'required|integer|exists:quotes,id',   
                'product_id'=> 'required|integer|exists:quotes,id',
                'quantity'=> 'required|numeric|min:1',
                // 'line_price' =>'nullable|numeric|min:0'

            ];
        }else {
            return [

                'quote_id' => 'sometimes|integer|exists:quotes,id',   
                'product_id'=> 'sometimes|integer|exists:quotes,id',
                'quantity'=> 'sometimes|numeric|min:1',
                // 'line_price' =>'sometimes|numeric|min:0'

            ];
        }
    }

    public function prepareForValidation() {
        if($this->quoteId){
            $this->merge([
                'quote_id' => $this->quoteId
            ]);
        }
        if($this->productId){ 
            $this->merge([
                'product_id' => $this->productId
            ]);
        }
        if($this->unitPrice){ 
            $this->merge([
                'unit_price' => $this->unitPrice
            ]);
        }
    }
}
