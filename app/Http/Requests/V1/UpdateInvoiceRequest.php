<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
                'customer_id' => ["required","exists:customers,id"],
                'amount' => ["required","numeric"],
                'status' => ['required'],
                'billed_date' => ["required","date"],
                'paid_date' => ["nullable","date","after:billed_date"]
            ];

        }else {
            return [
                'customer_id' => 'sometimes|required|exists:customers,id',
                'amount' => 'sometimes|required|numeric',
                'status' => 'sometimes|required',
                'billed_date' => 'sometimes|required|date',
                'paid_date' => 'sometimes|nullable|date|after:billed_date'
            ];
        
        }
    }

    public function prepareForValidation() {
        if($this->customerId){
            $this->merge([
                'customer_id' => $this->customerId
            ]);
        }
        if($this->billedDate){
            $this->merge([
                'billed_date' => $this->billedDate
            ]);
        }
        if($this->paidDate){
            $this->merge([
                'paid_date' => $this->paidDate
            ]);
        }
    }
}
