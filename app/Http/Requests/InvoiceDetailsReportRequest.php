<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceDetailsReportRequest extends FormRequest
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
            'invoice_number' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            //'invoice_number.required' => 'invoice number is required',
            'invoice_number.string' => 'invoice number must be string'
        ];
    }
}
