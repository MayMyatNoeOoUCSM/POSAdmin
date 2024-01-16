<?php

namespace App\Dao;

use App\Contracts\Dao\CompanyLicenseDaoInterface;
use App\Models\CompanyLicense;

/**
 * Company License Dao
 *
 * @author
 */
class CompanyLicenseDao implements CompanyLicenseDaoInterface
{

    /**
     * Get Company License List from table
     *
     * @return Object $companyLicenseList
     */
    public function getCompanyLicenseList($request)
    {
        $companyList = CompanyLicense::leftJoin('m_company as c', 'c.id', '=', 'm_company_license.company_id')
            ->select('m_company_license.*', 'c.name as company_name');
        if ($request->custom_pg_size) {
            $companyList = $companyList->paginate($request->custom_pg_size);
        } else {
            $companyList = $companyList->paginate(config('constants.COMPANY_LICENSE_PAGINATION'));
        }
        return $companyList;
    }

    /**
     * Get Company List from table
     *
     * @return Object $companyList
     */
    public function getAllCompanyLicenseList()
    {
        $companyList = CompanyLicense::leftJoin('m_company as c', 'c.id', '=', 'm_company_license.company_id')
            ->paginate(config('constants.COMPANY_LICENSE_PAGINATION'), ['m_company_license.*', 'c.name as company_name']);
        return $companyList;
    }

    /**
     * store company info into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $license
     */
    public function insert($request)
    {
        $license = new CompanyLicense();
        $license->company_id = $request->company_id;
        $license->start_date = $request->start_date;
        $license->end_date = $request->end_date;
        $license->status = $request->status;
        $license->user_count = $request->user_count;
        $license->license_type = $request->license_type;
        $license->payment_amount = $request->payment_amount;
        $license->discount_amount = $request->discount_amount;
        $license->same_company_contact_flag = $request->contact_check;
        $license->contact_person = $request->contact_person;
        if ($request->contact_check == 2) {
            $license->contact_email = $request->contact_email;
        } else {
            $license->contact_email = $request->contact_email ?? null;
        }
        $license->contact_phone = $request->contact_phone;
        $license->create_user_id = auth()->user()->id;
        $license->update_user_id = auth()->user()->id;
        $license->create_datetime = Date('Y-m-d H:i:s');
        $license->update_datetime = Date('Y-m-d H:i:s');
        $license->save();
        return $license;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Request\CompanyLicenseRequest $request
     * @param  \App\Models\CompanyLicense $license
     * @return Object $license
     */
    public function update($request, $license)
    {
        $license = CompanyLicense::find($license->id);
        $license->company_id = $request->company_id;
        $license->start_date = $request->start_date;
        $license->end_date = $request->end_date;
        $license->status = $request->status;
        $license->user_count = $request->user_count;
        $license->license_type = $request->license_type;
        $license->payment_amount = $request->payment_amount;
        $license->discount_amount = $request->discount_amount;
        $license->contact_person = $request->contact_person;
        $license->contact_phone = $request->contact_phone;
        $license->contact_email = $request->contact_email;
        $license->create_user_id = auth()->user()->id;
        $license->update_user_id = auth()->user()->id;
        $license->create_datetime = Date('Y-m-d H:i:s');
        $license->update_datetime = Date('Y-m-d H:i:s');
        $license->save();
        return $license;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CompanyLicense $license
     * @return Object $license
     */
    public function delete($license)
    {
        // $license = CompanyLicense::where('id', $license->id)->delete();
        // return $license;
    }
}
