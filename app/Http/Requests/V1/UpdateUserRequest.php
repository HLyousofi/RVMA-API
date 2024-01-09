<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

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
        $method = $this->method();
        if($method == 'PUT'){
            return [
                "name" => "required",
                "email" => "required|email|unique:users,email",
                "email_verified_at" => "email|unique:users,email",
                "password" => "required"
            ];
        } else {
            return [
                "name" => "sometimes",
                "email" => "sometimes|email|unique:users,email",
                "password" => "sometimes"
            ];
        }
    }
}
