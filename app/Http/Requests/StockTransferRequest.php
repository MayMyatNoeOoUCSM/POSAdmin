<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockTransferRequest extends FormRequest
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
            'selected_warehouse_id' => 'required',
            'qty' => 'required|numeric',
            'price' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'selected_warehouse_id.required' => 'warehouse stock is required',
            'qty.required'  => 'quantity is required',
            'price.required' => 'price is required',
            'qty.numeric'  => 'quantity must be number',
            'price.numeric'  => 'price must be number',
        ];
    }
}
