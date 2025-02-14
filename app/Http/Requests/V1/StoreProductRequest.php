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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'referance' => 'required|string|max:100|unique:products,referance',
            'price' => 'required|numeric|min:0',
            'unitInStock' => 'required|integer|min:0'
        ];
    }

    public function prepareForValidation() {
        $this->merge([
            'category_id' => $this->categoryId
        ]);
    }
}
