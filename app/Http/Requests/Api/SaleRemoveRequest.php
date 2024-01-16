<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SaleRemoveRequest extends FormRequest
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
            'sale_id' => 'required|numeric',
            'product_id'=>'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'sale_id.required' => 'sale id is required',
            'sale_id.numeric' => 'sale id must be number',
            'product_id.required' => 'product id is required',
            'product_id.numeric' => 'product id must be number'
        ];
    }
}
