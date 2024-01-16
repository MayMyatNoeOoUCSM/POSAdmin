<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SaleInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'change_amount' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'id is required',
            'id.numeric' => 'id must be numeric',
            'paid_amount.required' => 'paid amount is required',
            'paid_amount.numeric' => 'paid amount must be numeric',
            'change_amount.required' => 'change amount is required',
            'change_amount.numeric' => 'change amount must be numeric'
        ];
    }
}
