<?php

namespace App\Contracts\Services;

interface CompanyLicenseServiceInterface
{

    /**
     * Get Company list
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $CompanyList
     */
    public function getCompanyLicenseList($request);

    /**
     * Get all Company list
     *
     * @return Object CompanyList
     */
    public function getAllCompanyLicenseList();

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
     * @param \App\Models\Company $company
     * @return Object $license
     */
    public function delete($license);
}
