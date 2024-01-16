<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TerminalRequest extends FormRequest
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
                'shop_id'   => 'required|integer',
                'name'  =>  'required|unique:m_terminal,name,'.(int)$this->segment(2).',id,shop_id,'.request()->input('shop_id')
            ];
        }
        return [
            'shop_id'   => 'required|integer',
            'name'  =>  'required|unique:m_terminal,name,NULL,id,shop_id,'.(request()->input('shop_id')??0)
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'terminal name is required',
            'shop_id.required' => 'shop is required'
        ];
    }
}
