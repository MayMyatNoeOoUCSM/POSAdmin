<?php

namespace App\Contracts\Dao;

/**
 * CompanyLicenseDaoInterface
 */
interface CompanyLicenseDaoInterface
{
    /**
     * Get Company License List from storage
     *
     * @return Object CompanyList
     */
    public function getAllCompanyLicenseList();

    /**
     * Get Company License List from storage
     *
     * @return Object CompanyLicenseList
     */
    public function getCompanyLicenseList($request);

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Object $license
     */
    public function insert($request);

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Request\CompanyLicenseRequest $request
     * @param  \App\Models\CompanyLicense $license
     * @return Object $license
     */
    public function update($request, $license);

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CompanyLicense $license
     * @return Object $license
     */
    public function delete($license);
}
