<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            "last_name" => "required|string",
            "first_name" => "required|string",
            "phone_number" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required"
        ];
    }

    public function prepareForValidation(){
         return $this->merge([
           "phone_number" => $this->phoneNumber,
           "last_name" => $this->lastName,
           "first_name" => $this->firstName,
       ]);
   }

}
