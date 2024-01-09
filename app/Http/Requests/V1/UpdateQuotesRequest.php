<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuotesRequest extends FormRequest
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
                'amount' => 'required|numeric',
                'status' => 'required',
            ];
        }else {
            return [
                'vehicle_id' => 'sometimes|required|integer|exists:vehicles,id',   
                'amount' => 'sometimes|required|numeric',
                'status' => 'sometimes|required',
            ];
        }
    }

    public function prepareForValidation() {
        if($this->vehicleId){
            $this->merge([
                'vehicle_id' => $this->vehicleId
            ]);
        }
    }
}
