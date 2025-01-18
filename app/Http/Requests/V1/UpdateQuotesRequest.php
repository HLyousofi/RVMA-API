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
                'status' => 'required|string|in:draft,pending,approved,rejected',
                'creation_date'=> 'required|date|date_format:Y-m-d',
                'expiration_date'=> 'required|date|after_or_equal:creation_date',   
                'comment' => 'nullable|string|max:1000',
            ];
        }else {
            return [
                'vehicle_id' => 'sometimes|integer|exists:vehicles,id',
                'status' => 'sometimes|string|in:draft,pending,approved,rejected',
                'creation_date'=> 'sometimes|date',
                'expiration_date'=> 'sometimes|date|after_or_equal:creation_date', 
                'comment' => 'nullable|string|max:1000',
            ];
        }
    }

    public function prepareForValidation()
    {
            if($this->vehicleId){
                $this->merge([
                    'vehicle_id' => $this->vehicleId
                ]);
            }
            if($this->creationDate){
                $this->merge([
                    'creation_date' => $this->creationDate
                ]);
            }

            if($this->expirationDate){
                $this->merge([
                    'expiration_date' => $this->expirationDate
                ]);
            }
    }
}
