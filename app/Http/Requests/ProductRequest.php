<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
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
        if (request()->isMethod('put')) { // could be patch as well
            return [
                'name' => 'required|max:100|unique:m_product,name,'.(int)$this->product->id.',id,product_type_id,' . request()->input('product_category_id').',product_status,!3,company_id,'.Auth::user()->company_id,
                'product_category_id' => 'required|not_in:0',
                'shop_id' =>'present|array',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'min_qty' => 'nullable|numeric',
                'expire_date' => 'nullable|after:mfd_date',
                'sale_price' => 'nullable|numeric',
                'short_name' => 'required|max:20|unique:m_product,short_name,'.(int)$this->product->id.',id,product_type_id,' . request()->input('product_category_id').',product_status,!3,company_id,'.Auth::user()->company_id,
            ];
        }
        return [
            'product_category_id' => 'required|not_in:0',
            'shop_id' =>'present|array',
            'name' => 'required|max:100|unique:m_product,name,NULL,id,product_type_id,' . request()->input('product_category_id').',product_status,!3,company_id,'.Auth::user()->company_id,
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'expire_date' => 'nullable|after:mfd_date',
            'min_qty' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'short_name' => 'required|max:20|unique:m_product,short_name,NULL,id,product_type_id,' . request()->input('product_category_id').',product_status,!3,company_id,'.Auth::user()->company_id,

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'product name is required',
            'name.unique' => 'product name has already been taken',
            'product_category_id.required' => 'category is required',
            'product_category_id.not_in' => 'category is required to select',
            'shop_id.present' => 'shop name need to select one',
            'shop_id.array' => 'shop name must be array',
            'min_qty.required' => 'minimum quantity is required',
            'expire_date.after' => 'the expired date must be greater than manufacture date',
            'image.image' => 'product image must be jpeg,png,jpg,gif,svg file types',
            'image.uploaded' => 'product image size must be less than 2 MB',
            'sale_price.required' => 'sale price is required',
            'short_name.required' => 'short name is required',
            'short_name.unique' => 'short name has already been taken'

        ];
    }
}
