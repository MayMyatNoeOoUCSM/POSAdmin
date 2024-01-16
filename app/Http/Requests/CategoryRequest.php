<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CategoryRequest extends FormRequest
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
        $id = '';
        if ($this->category) {
            $id = $this->category->id ? ',' . $this->category->id : '';
        } else {
            $id = ',null';
        }

        return [
            'name' => 'required|max:100|unique:m_category,name' . $id.',id,company_id,'.Auth::user()->company_id,
            'description' => 'max:150',
            'shop_id' =>'present|array',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'category name is required',
            'shop_id.present' => 'shop name need to select one',
            'shop_id.array' => 'shop name must be array',
        ];
    }
}
