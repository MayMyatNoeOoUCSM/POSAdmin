<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyLicenseRequest extends FormRequest
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
                'company_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'license_type' => 'required',
                'contact_email'=> 'required',
                'payment_amount' => 'required',
                'status' => 'required',
                'user_count' => 'required'

            ];
        }
        return [
            'company_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'license_type' => 'required',
            'contact_email'=> 'required',
            'payment_amount' => 'required',
            'status' => 'required',
            'user_count' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'company_id.required' => 'company name is required',
            'start_date.required' => 'start date is required',
            'end_date.required' => 'end date is required',
            'license_type.required' => 'license type is required',
            'payment_amount.required' => 'payment amount is required',
            'contact_email.required' => 'contact email is required',
            'status.required' => 'status is required',
            'user_count.required' => 'user count is required'
        ];
    }
}
