<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseRequest extends FormRequest
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
            'address' => 'required',
            'phone_number_1' => 'nullable|numeric',
            'phone_number_2' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'address.required' => 'address is required',
            'phone_number_1.required' => 'phone number1 is required',
            'phone_number_2.required' => 'phone number2 is required',
        ];
    }
}
