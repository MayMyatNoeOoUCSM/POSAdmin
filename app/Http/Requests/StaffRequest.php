<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
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
        if ($this->role == config('constants.SHOP_ADMIN') or
            $this->role == config('constants.CASHIER_STAFF') or
            $this->role == config('constants.KITCHEN_STAFF') or
            $this->role == config('constants.WAITER_STAFF') or
            $this->role == config('constants.SALE_STAFF')) {
            return [
                'name' => 'required|max:100',
                'password' => 'nullable|min:8|max:100|confirmed',
                'role' => 'required',
                'type' => 'required',
                'position' => 'required',
                'gender' => 'required',
                'nrc_number' => 'required',
                'marital_status' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'ph_no1' => 'nullable|numeric',
                'ph_no2' => 'nullable|numeric',
                'join_from' => 'required',
                'shop_id' => 'required',
            ];
        }
        if ($this->role == config('constants.COMPANY_ADMIN')) {
            return [
                'name' => 'required|max:100',
                'password' => 'nullable|min:8|max:100|confirmed',
                'role' => 'required',
                'type' => 'required',
                'position' => 'required',
                'gender' => 'required',
                'nrc_number' => 'required',
                'marital_status' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'ph_no1' => 'nullable|numeric',
                'ph_no2' => 'nullable|numeric',
                'join_from' => 'required',
                'company_id' => 'required',
            ];
        }
        return [
            'name' => 'required|max:100',
            'password' => 'nullable|min:8|max:100|confirmed',
            'role' => 'required',
            'type' => 'required',
            'position' => 'required',
            'gender' => 'required',
            'nrc_number' => 'required',
            'marital_status' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ph_no1' => 'nullable|numeric',
            'ph_no2' => 'nullable|numeric',
            'join_from' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'staff name is required',
            'role.required' => 'staff role is required',
            'type.required' => 'staff type is required',
            'password.required' => 'password is required',
            'position.required' => 'staff position is required',
            'nrc_number.required' => 'nrc no is required',
            'gender.required' => 'gender is required',
            'marital_status.required' => 'marital status is required',
            'join_from.required' => 'join from is required',
            'image.image' => 'profile image must be jpeg,png,jpg,gif,svg file types',
            'image.uploaded' => 'profile image size must be less than 2 MB',
            'shop_id.required' => 'shop need to select',
            'company_id.required' => 'company need to select'
        ];
    }
}
