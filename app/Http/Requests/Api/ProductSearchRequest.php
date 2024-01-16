<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProductSearchRequest extends FormRequest
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
            'search_name' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            //'search_name.required' => 'search number is required',
            'search_name.string' => 'search number must be string'
        ];
    }
}
