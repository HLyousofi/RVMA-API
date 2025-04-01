<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
            'company_name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'ice_number' => 'nullable|string|max:14',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function prepareForValidation() {
        if($this->companyName){
            $this->merge([
                'company_name' => $this->companyName
            ]);
        }
        if($this->iceNumber){ 
            $this->merge([
                'ice_number' => $this->iceNumber
            ]);
        }
        if($this->logoPath){ 
            $this->merge([
                'logo_path' => $this->logoPath
            ]);
        }
    }
}
