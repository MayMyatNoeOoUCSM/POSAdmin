<?php

namespace App\Services;

use App\Contracts\Dao\CompanyLicenseDaoInterface;
use App\Contracts\Services\CompanyLicenseServiceInterface;

class CompanyLicenseService implements CompanyLicenseServiceInterface
{
    private $companyLicenseDao;

    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\CompanyDaoInterface $companyLicenseDao
     * @return void
     */
    public function __construct(CompanyLicenseDaoInterface $companyLicenseDao)
    {
        $this->companyLicenseDao = $companyLicenseDao;
    }

    /**
     * Get company list
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $companyList
     */
    public function getCompanyLicenseList($request)
    {
        return $this->companyLicenseDao->getCompanyLicenseList($request);
    }

    /**
     * Get all company list
     *
     * @return Object $companyList
     */
    public function getAllCompanyLicenseList()
    {
        return $this->companyLicenseDao->getAllCompanyLicenseList();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Object $license
     */
    public function insert($request)
    {
        return $this->companyLicenseDao->insert($request);
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
        return $this->companyLicenseDao->update($request, $license);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Company $license
     * @return Object $license
     */
    public function delete($license)
    {
        return $this->companyLicenseDao->delete($license);
    }
}
