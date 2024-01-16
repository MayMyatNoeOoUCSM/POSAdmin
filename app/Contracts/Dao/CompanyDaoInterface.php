<?php

namespace App\Contracts\Dao;

/**
 * CompanyDaoInterface
 */
interface CompanyDaoInterface
{

    /**
     * Get Company List with pagination from table
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Object $companyList
     */
    public function getCompanyList($request);

    /**
    * Get All Company List from table
    *
    * @return Object $companyList
    */
    public function getAllCompanyList();

    /**
      * store company info into storage
      *
      * @param \Illuminate\Http\Request $request
      * @return Object $company
      */
    public function insert($request);

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Request\CompanyRequest $request
     * @param  \App\Models\Company $company
     * @return Object $company
    */
    public function update($request, $company);

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Company $company
     * @return Object $company
     */
    public function delete($company);

    /**
    * Get data for report export excel from storage
    *
    * @param \Illuminate\Http\Request $request
    * @return Object
     */
    public function getCompanyLicenseDataExport($request);

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getCompanyDataExport($request);

    /**
     * Get total company count
     *
     * @return Integer
     */
    public function getTotalCompany();

    /**
     * Get total active company count
     *
     * @return Integer
    */
    public function getTotalActiveCompany();
    /**
    * Get total expire company count
    *
    * @return Integer
    */
    public function getTotalExpireCompany();
}
