<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user'); 
            return [
                "email" => ["sometimes","email",Rule::unique('users', 'email')->ignore($userId)],
                "password" => "sometimes",
                "last_name" => "sometimes",
                "first_name" => "sometimes",
                "phone_number" => "sometimes",
            ];
        
    }

    public function prepareForValidation(){
        if($this->phoneNumber){
            $this->merge([
                'phone_number' => $this->phoneNumber,
            ]);
        }
        if($this->lastName){
            $this->merge([
                'last_name' => $this->lastName,
            ]);
        }
      if($this->firstName){
            $this->merge([
                'first_name' => $this->firstName,
            ]);
        }
       
   }
}
