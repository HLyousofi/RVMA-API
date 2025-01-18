<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotesRequest extends FormRequest
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
            'vehicle_id' => 'required|integer|exists:vehicles,id',
            'creation_date'=> 'required|date',
            'expiration_date'=> 'required|date|after_or_equal:creation_date',   
            'status' => 'string|in:draft,pending,approved,rejected',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function prepareForValidation() {
        $this->merge([
            'vehicle_id' => $this->vehicleId,
            'creation_date' => $this->creationDate,
            'expiration_date' => $this->expirationDate
        ]);
    }
}
