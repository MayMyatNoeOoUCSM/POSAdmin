<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
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
                'shop_id' => 'required|integer',
                'name' => 'required|unique:m_restaurant_table,name,' . (int) $this->segment(2) . ',id,shop_id,' . request()->input('shop_id'),
                'total_seat_people' => 'required|integer'
            ];
        }
        return [
            'shop_id' => 'required|integer',
            'name' => 'required|unique:m_restaurant_table,name,NULL,id,shop_id,' . (request()->input('shop_id')??0),
            'total_seat_people' => 'required|integer'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'restaurant table name is required',
            'shop_id.required' => 'shop name is required',
            'total_seat_people.required' => 'total seat people is required'
        ];
    }
}
