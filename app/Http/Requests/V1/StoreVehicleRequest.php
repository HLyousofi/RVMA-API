<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
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
            'customer_id' => 'required|integer|exists:customers,id',
            'brand' => 'required|string',
            'model' => 'required|string',
            'plate_number' => 'required|string',
            'fuel_type' => 'required|string'
        ];
    }

    public function prepareForValidation(){
        return $this->merge([
            'customer_id' => $this->customerId,
            'plate_number' => $this->plateNumber,
            'fuel_type' => $this->fuelType
        ]);
    }
}
