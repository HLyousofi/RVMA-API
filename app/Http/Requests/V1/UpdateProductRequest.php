<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


class UpdateProductRequest extends FormRequest
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
        // Get the product ID from the route (assuming the route parameter is 'product')
        $productId = $this->route('product');

        return [
            'name' => [
                'sometimes',                                    // Optional field
                'string',                                       // Must be a string
                'max:255',                                      // Max length 255 characters
                Rule::unique('products', 'name')->ignore($productId), // Unique in 'products' table, ignoring current product
            ],
            'category_id' => [
                'sometimes', // Only validate if provided
                'nullable',  // Allow null if category is optional
                'integer',
                'exists:categories,id' // Ensures category_id exists in categories table
            ],
            // 'brand' => 'sometimes|string|max:255',
            // 'model' => 'sometimes|string|max:255',
            // 'manufacturer_reference' => 'sometimes|string|max:255',
            // 'oem_reference' => 'sometimes|string|max:255',
            // 'description' => 'sometimes|string',
        ];
    }

    /**
     * Get custom messages for validation errors (optional).
     */
    public function messages(): array
    {
        return [
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'name.unique' => 'The name has already been taken.',
            'brand.string' => 'The brand must be a string.',
            'brand.max' => 'The brand may not be greater than 255 characters.',
            'model.string' => 'The model must be a string.',
            'model.max' => 'The model may not be greater than 255 characters.',
            'manufacturer_reference.string' => 'The manufacturer reference must be a string.',
            'manufacturer_reference.max' => 'The manufacturer reference may not be greater than 255 characters.',
            'oem_reference.string' => 'The OEM reference must be a string.',
            'oem_reference.max' => 'The OEM reference may not be greater than 255 characters.',
            'description.string' => 'The description must be a string.',
        ];
    }
   
    public function prepareForValidation() {
       
            
            if($this->categoryId){
                $this->merge([
                    'category_id' => $this->categoryId
                ]);
            }
            if($this->oemReference){
                $this->merge([
                    'oem_reference' => $this->oemReference
                ]);
            }if($this->manufacturerReference){
                $this->merge([
                    'manufacturer_reference' => $this->manufacturerReference
                ]);
            }
            
        
    }
}
