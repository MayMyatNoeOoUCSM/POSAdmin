<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShopRequest extends FormRequest
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
        if (request()->isMethod('put')) {
            return [
                'name' => 'required|unique:m_shop,name,' . (int) $this->segment(2) . ',id,company_id,'.Auth::user()->company_id.'|max:100',
                'address' => 'required|max:300',
                'shop_type' => 'required',
                'phone_number_1' => 'nullable|numeric',
                'phone_number_2' => 'nullable|numeric',
            ];
        }
        return [
            'name' => 'required|unique:m_shop,name,null,id,company_id,'.Auth::user()->company_id.'|max:100',
            'address' => 'required|max:300',
            'shop_type' => 'required',
            'phone_number_1' => 'nullable|numeric',
            'phone_number_2' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'shop name is required',
            'address.required' => 'address is required',
            'shop_type.required' => 'shop type is required',
        ];
    }
}
