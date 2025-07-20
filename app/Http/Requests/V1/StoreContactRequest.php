<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
            'contacts' => 'sometimes|array',
            'contacts.*.last_name' => 'required|string|max:255',
            'contacts.*.first_name' => 'required|string|max:255',
            'contacts.*.email' => 'nullable|email|max:255|unique:contacts,email',
            'contacts.*.phone_number' => 'nullable|string|max:20',
            'contacts.*.job_title' => 'nullable|string|max:255',
            'contacts.*.address' => 'nullable|string',
        ];
    }

    public function prepareForValidation(){
         // Get the customerContacts array from the request
         $customerContacts = $this->input('contacts', []);
    
         // Transform each product quote
         $transformedcustomerContacts = array_map(function ($item) {
             return [
                 'email' => $item['email'] ?? null,
                 'address' => $item['address'] ?? null,
                 'last_name' => $item['lastName'] ?? null,
                 'first_name' => $item['firstName'] ?? null, 
                 'phone_number' => $item['phoneNumber'] ?? null, 
                 'job_title' => $item['jobTitle'] ?? null, 

             ];
         }, $customerContacts);
     
         // Merge the transformed data back into the request
         $this->merge([
             'contacts' => $transformedcustomerContacts,
         ]);



    }
      
}
