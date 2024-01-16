<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
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
            'order_id' => 'required|numeric',
            'data'  => 'required|array|min:1',
            'data.product_id'=>'required',
            'data.product_id.*'=>'required|numeric',
            'data.price'=>'required',
            'data.price.*'=>'required|numeric',
            'data.quantity'=>'required',
            'data.quantity.*'=>'required|numeric'


        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => 'order id is required',
            'order_id.numeric' => 'order id must be number'
            //'data.product_id.required'  => 'product id is required'
        ];
    }
}
