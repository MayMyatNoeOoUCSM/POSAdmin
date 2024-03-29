<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
                'name' => 'required|unique:m_company,name,' . (int) $this->segment(2) . '|max:100',
                'address' => 'required|max:300',
            ];
        }
        return [
            'name' => 'required|unique:m_company,name|max:100',
            'address' => 'required|max:300',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'company name is required',
            'address.required' => 'address is required',
        ];
    }
}
