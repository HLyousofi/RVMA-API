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
        return [
            'customer_id' => 'sometimes|integer|exists:customers,id', // "sometimes" rend le champ optionnel
            'vehicle_id' => 'sometimes|integer|exists:vehicles,id',
            'discount' => 'sometimes|numeric|min:0',
            'billed_date' => 'sometimes|date_format:Y-m-d H:i:s', // Optionnel mais format strict si fourni
            'amount' => 'sometimes|numeric|min:0',
            'paid_date' => 'sometimes|date_format:Y-m-d H:i:s',
            'status' => 'sometimes|in:rejected,draft,converted,in_progress,completed,to_invoice',

        ];
    }

    public function prepareForValidation() {
        if($this->customerId){
            $this->merge([
                'customer_id' => $this->customerId
            ]);
        }
        if($this->vehicleId){
            $this->merge([
                'vehicle_id' => $this->vehicleId
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
