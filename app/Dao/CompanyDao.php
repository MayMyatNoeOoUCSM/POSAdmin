<?php

namespace App\Dao;

use App\Contracts\Dao\CompanyDaoInterface;
use App\Models\Company;
use App\Models\CompanyLicense;
use Illuminate\Support\Facades\DB;

/**
 * Company Dao
 *
 * @author
 */
class CompanyDao implements CompanyDaoInterface
{

    /**
     * Get Company List with pagination from table
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Object $companyList
     */
    public function getCompanyList($request)
    {
        $companyList = Company::where('is_deleted', config('constants.DEL_FLG_OFF'))
            ->paginate($request->custom_pg_size == "" ? config('constants.COMPANY_PAGINATION') : $request->custom_pg_size);
        return $companyList;
    }

    /**
     * Get All Company List from table
     *
     * @return Object $companyList
     */
    public function getAllCompanyList()
    {
        $companyList = Company::where('is_deleted', config('constants.DEL_FLG_OFF'))->get();
        return $companyList;
    }

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getCompanyLicenseDataExport($request)
    {
        $companylicense = CompanyLicense::join("m_company", "m_company.id", "=", "m_company_license.company_id")
            ->select(\DB::raw("m_company.name as company_name, m_company_license.start_date as start_date, m_company_license.end_date as end_date, (CASE WHEN m_company_license.license_type = '1' THEN 'STANDALONE POS' WHEN m_company_license.license_type = '2' THEN 'STANDALONE POS INVENTORY' WHEN m_company_license.license_type = '3' THEN 'MULTI POS' ELSE 'MULTI POS INVENTORY' END) AS license_type, (CASE WHEN m_company_license.status = '1' THEN 'COMPANYLICENSE INACTIVE' WHEN m_company_license.status = '2' THEN 'COMPANYLICENSE ACTIVE' WHEN m_company_license.status = '3' THEN 'COMPANYLICENSE EXPIRE' ELSE 'COMPANYLICENSE BLOCK' END) AS status, m_company_license.payment_amount as payment, m_company_license.discount_amount as discount"))
            ->where(function ($q) use ($request) {
                if (! empty($request->select_company_name)) {
                    $q->where("m_company.id", "=", $request->select_company_name);
                }
                if (! empty($request->from_date)) {
                    $q->whereDate('m_company_license.start_date', '>=', $request->from_date);
                }

                if (! empty($request->to_date)) {
                    $q->whereDate('m_company_license.end_date', '<=', $request->to_date);
                }
            })
            ->get();
        return $companylicense;
    }

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getCompanyDataExport($request)
    {
        $company = DB::table('m_company')
            ->select(\DB::raw("m_company.name as company_name, m_company.address, m_company.phone_number_1 as primary_phone, m_company.phone_number_2 as secondary_phone"))
            ->where(function ($q) use ($request) {
                if (! empty($request->select_company_name)) {
                    $q->where("m_company.id", "=", $request->select_company_name);
                }
                if (! empty($request->from_date)) {
                    $q->whereDate('m_company.create_datetime', '>=', $request->from_date);
                }

                if (! empty($request->to_date)) {
                    $q->whereDate('m_company.create_datetime', '<=', $request->to_date);
                }
            })
            ->get();
        return $company;
    }

    /**
     * Store company info into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $company
     */
    public function insert($request)
    {
        $company = new Company();
        $company->name = $request->name;
        $company->address = $request->address;
        $company->company_logo_path = $request->image;
        $company->phone_number_1 = $request->primary_phone;
        $company->phone_number_2 = $request->secondary_phone;
        $company->is_deleted = config('constants.DEL_FLG_OFF');
        $company->create_user_id = auth()->user()->id;
        $company->update_user_id = auth()->user()->id;
        $company->create_datetime = Date('Y-m-d H:i:s');
        $company->update_datetime = Date('Y-m-d H:i:s');
        $company->save();
        return $company;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Request\CompanyRequest $request
     * @param  \App\Models\Company $company
     * @return Object $company
     */
    public function update($request, $company)
    {
        $company = Company::find($company->id);
        $company->name = $request->name;
        $company->address = $request->address;
        $company->company_logo_path = $request->image;
        $company->phone_number_1 = $request->primary_phone;
        $company->phone_number_2 = $request->secondary_phone;
        $company->is_deleted = config('constants.DEL_FLG_OFF');
        $company->create_user_id = auth()->user()->id;
        $company->update_user_id = auth()->user()->id;
        $company->create_datetime = Date('Y-m-d H:i:s');
        $company->update_datetime = Date('Y-m-d H:i:s');
        $company->save();
        return $company;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Company $company
     * @return Object $company or Boolean false
     */
    public function delete($company)
    {
        $company = Company::where('m_company.id', $company->id)
            ->update(['m_company.is_deleted'=>config('constants.DEL_FLG_ON')]);
        return $company;
    }

    /**
     * Get total company count
     *
     * @return Integer
     */
    public function getTotalCompany()
    {
        return Company::where('m_company.is_deleted', config('constants.DEL_FLG_OFF'))
            ->get()->count();
    }

    /**
     * Get total active company count
     *
     * @return Integer
     */
    public function getTotalActiveCompany()
    {
        return Company::join('m_company_license', 'm_company_license.company_id', '=', 'm_company.id')
            ->where('m_company_license.status', '=', config('constants.COMPANY_LICENSE_ACTIVE'))
            ->get()->count();
    }
    /**
     * Get total expire company count
     *
     * @return Integer
     */
    public function getTotalExpireCompany()
    {
        return Company::join('m_company_license', 'm_company_license.company_id', '=', 'm_company.id')
            ->where('m_company_license.status', '=', config('constants.COMPANY_LICENSE_EXPIRE'))
            ->get()->count();
    }
}
