<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
                'vehicle_id' => 'required|integer|exists:vehicles,id',
                'invoice_id'=> 'nullable|integer|exists:invoices,id',
                'quote_id' => 'nullable|integer|exists:quotes,id',
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'task'=> 'nullable|json',
                'status' => 'required|string'
                ];

        }else {
            return [
                'vehicle_id' => 'sometimes|required|integer|exists:vehicles,id',
                'invoice_id'=> 'sometimes|nullable|integer|exists:invoices,id',
                'quote_id' => 'sometimes|nullable|integer|exists:quotes,id',
                'name' => 'sometimes|required|string',
                'description' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric',
                'task'=> 'sometimes|nullable|json',
                'status' => 'sometimes|required|string'
                ];

        }
       
    }

    public function prepareForValidation() {
        if($this->vehicleId){
            $this->merge([
                'vehicle_id' => $this->vehicleId
            ]);
        }
        if($this->invoiceId){
            $this->merge([
                'invoice_id' => $this->invoiceId
            ]);
        }
        if($this->quoteId){
            $this->merge([
                'quote_id' => $this->quoteId
            ]);
        }
       
    }
}
