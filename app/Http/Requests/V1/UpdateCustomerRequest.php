<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
                'name' => ['required'],
                'type' => ['required'],
                'adress' => ['required'],
                'email' => ["required","email","unique:customers,email"],
                'phone_number' => ['required','unique:customers,phone_number'],
                'ice' => 'nullable'
            ];
        }else {
            return [
                'name' => 'sometimes|required',
                'type' => 'sometimes|required',
                'adress' => 'sometimes|required',
                'email' => 'sometimes|required|email',
                'phone_number' => 'sometimes|required',
                'ice' => 'sometimes|nullable'
            ];

        }
    }

    public function prepareForValidation(){
        if($this->phoneNumber){
             $this->merge([
                'phone_number' => $this->phoneNumber
            ]);
        }
        // if($this->customInfo){
        //      $this->merge([
        //         'custom_info' => $this->customInfo
        //     ]);
        // }
        
    }
}
