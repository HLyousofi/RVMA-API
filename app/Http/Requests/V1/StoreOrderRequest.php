<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
        'invoice_id'=> 'nullable|integer|exists:invoices,id',
        'quote_id' => 'nullable|integer|exists:quotes,id',
        'name' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'task'=> 'nullable|json',
        'status' => 'required|string'
        ];
    }

    public function prepareForValidation() {
        $this->merge([
            'vehicle_id' => $this->vehicleId,
            'invoice_id' => $this->invoiceId,
            'quote_id' => $this->quoteId
        ]);
    }
}
