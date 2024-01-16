<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleReturnRequest extends FormRequest
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

            'qty.*' => 'required|numeric',
            'reason.*' => 'required',
            'damage_qty.*' => 'required_if:damageChk.*,1',
            'return_date' =>  'required'

        ];
    }
}
