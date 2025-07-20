<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => [
                'nullable',  // Allow null if category is optional
                'integer',
                'exists:categories,id' // Ensures category_id exists in categories table
            ],
            'category_id'=> 'nullable|integer',
            'brand' => 'nullable|string',
            'manufacturer_reference' => 'nullable|string|unique:products,manufacturer_reference',
            'oem_reference'=> 'nullable|string|unique:products,oem_reference',
            'description' => 'nullable|string',
            'model' => 'nullable|string|max:100',
            // 'purchase_price' => 'nullable|numeric|min:0',
            // 'sale_price' => 'nullable|numeric|min:0'
        ];
    }

    public function prepareForValidation() {
        $this->merge([
            'manufacturer_reference' => $this->manufacturerReference,
            'oem_reference' => $this->oemReference
        ]);
    }
}
