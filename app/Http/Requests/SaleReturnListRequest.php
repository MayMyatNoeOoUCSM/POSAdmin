<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleReturnListRequest extends FormRequest
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
            'search_sale_date_to' => 'nullable|after_or_equal:search_sale_date_from',
        ];
    }

    public function messages()
    {
        return [
            'search_sale_date_to.after_or_equal' => 'To date must be greater than from date!',
        ];
    }
}
