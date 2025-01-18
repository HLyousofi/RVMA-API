<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
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
                'customer_id' => 'required|integer|exists:customers,id',
                'brand' => 'required|integer',
                'model' => 'required|string',
                'plate_number' => 'required|string',
                'fueltype_id' => 'required|integer'
            ];
        }else {
            return [
                'customer_id' => 'sometimes|required|integer|exists:customers,id',
                'brand' => 'sometimes|required|integer',
                'model' => 'sometimes|required|string',
                'plate_number' => 'sometimes|required|string',
                'fueltype_id' => 'sometimes|required|integer'
            ];
        }
    }

    public function prepareForValidation() {
        if($this->customerId){
            $this->merge([
                'customer_id' => $this->customerId
            ]);
        }
        if($this->plateNumber){ 
            $this->merge([
                'plate_number' => $this->plateNumber
            ]);
        }
        if($this->fuelType){ 
            $this->merge([
                'fueltype_id' => $this->fuelType
            ]);
        }
        if($this->brand){ 
            $this->merge([
                'brand_id' => $this->brand
            ]);
        }
    }
}
