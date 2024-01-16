<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleProductReportRequest extends FormRequest
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
            'to_date' => 'nullable|after_or_equal:from_date',
        ];
    }

    public function messages()
    {
        return [
            'to_date.after_or_equal' => 'To date must be greater than from date!',
        ];
    }
}
