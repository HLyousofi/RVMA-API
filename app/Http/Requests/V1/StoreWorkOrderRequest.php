<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkOrderRequest extends FormRequest
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
            'quote_number' => 'nullable',
            'customer_id' => 'required|integer|exists:customers,id',
            'vehicle_id' => 'required|integer|exists:vehicles,id',
            'type' => 'required|in:quote,order',
            'expiration_date' => 'nullable|date',
            'total' => 'nullable|numeric|min:0',
            'invoice_id' => 'nullable|exists:invoices,id',
            'order_date' => 'nullable|date',
            'current_mileage' => 'nullable|numeric|min:0',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'nullable|in:pending,approved,rejected,draft,converted',
            'expiration_date' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'The customer ID is required.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'vehicle_id.required' => 'The vehicle ID is required.',
            'vehicle_id.exists' => 'The selected vehicle does not exist.',
            'expiration_date.required' => 'The expiration date is required.',
            'expiration_date.date_format' => 'The expiration date must be in YYYY-MM-DD HH:MM:SS format.',
        ];
    }

   
    public function prepareForValidation()
    {
        // Préparer les données principales
        $this->merge([
            'customer_id' => $this->customerId,
            'vehicle_id' => $this->vehicleId,
            'expiration_date' => $this->expirationDate,
            'current_mileage' => $this->currentMileage
        ]);

        // if($this->type == ){
        //     $this-merge([
        //         'order_date' => $this->orderDate
        //     ]);
        // };

        if($this->deliveryDate){
            $this-merge([
                'delivery_date' => $this->deliveryDate
            ]);
        };
    }
}
