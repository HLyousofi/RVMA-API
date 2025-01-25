<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255|unique:products,name,' . $this->route('product'),
            'description' => 'nullable|string',
            'referance' => 'sometimes|string|max:100|unique:products,referance,' . $this->route('product'),
            'price' => 'sometimes|numeric|min:0',
            'unitInStock' => 'sometimes|integer|min:0'
        ];
    }
}
