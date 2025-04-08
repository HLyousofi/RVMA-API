<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Vous pouvez ajuster selon vos besoins d'autorisation
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
            'type' => 'sometimes|in:quote,order',
            'expiration_date' => 'sometimes|date_format:Y-m-d H:i:s', // Optionnel mais format strict si fourni
            'total' => 'nullable|numeric|min:0',
            'invoice_id' => 'nullable|exists:invoices,id',
            'order_date' => 'nullable|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'sometimes|in:pending,approved,rejected,draft',
        ];
    }


    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_id.exists' => 'The selected customer does not exist.',
            'vehicle_id.exists' => 'The selected vehicle does not exist.',
            'expiration_date.date_format' => 'The expiration date must be in YYYY-MM-DD HH:MM:SS format.',
            'type.in' => 'The type must be either "quote" or "order".',
            'status.in' => 'The status must be one of: pending, approved, rejected, draft.',
            'delivery_date.after_or_equal' => 'The delivery date must be on or after the order date.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation()
    {
      
        if($this->expirationDate){
            $this->merge([
               'expiration_date' => $this->expirationDate
           ]);
       }

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

        // Normaliser les dates optionnelles
        if ($this->orderDate || $this->order_date) {
            $this->merge([
                'order_date' => $this->orderDate ?? $this->order_date,
            ]);
        }

        if ($this->deliveryDate || $this->delivery_date) {
            $this->merge([
                'delivery_date' => $this->deliveryDate ?? $this->delivery_date,
            ]);
        }
    }
}