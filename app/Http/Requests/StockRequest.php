<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'product_id.*' => 'required',
            'warehouse_id.*' => 'required_if:w_s,0',
            'shop_id.*' => 'required_if:w_s,1',
            'qty.*' => 'required|numeric',
            'min_qty.*' => 'required|numeric',
            'price.*' => 'required|numeric',
        ];
    }
}
