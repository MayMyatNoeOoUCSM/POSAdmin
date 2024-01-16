<?php

namespace App\Services;

use App\Contracts\Dao\CompanyDaoInterface;
use App\Contracts\Services\CompanyServiceInterface;
use Image;

class CompanyService implements CompanyServiceInterface
{
    private $companyDao;

    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\CompanyDaoInterface $companyDao
     * @return void
     */
    public function __construct(CompanyDaoInterface $companyDao)
    {
        $this->companyDao = $companyDao;
    }

    /**
     * Get Company List with pagination from table
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Object $companyList
     */
    public function getCompanyList($companyCriteria)
    {
        return $this->companyDao->getCompanyList($companyCriteria);
    }

    /**
    * Get All Company List from table
    *
    * @return Object $companyList
    */
    public function getAllCompanyList()
    {
        return $this->companyDao->getAllCompanyList();
    }

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getCompanyLicenseDataExport($request)
    {
        return $this->companyDao->getCompanyLicenseDataExport($request);
    }

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getCompanyDataExport($request)
    {
        return $this->companyDao->getCompanyDataExport($request);
    }

    /**
     * Store company info into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $company
     */
    public function insert($request)
    {
        if (! empty($request->image)) {
            $request = $this->savedImage($request);
        }
        return $this->companyDao->insert($request);
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
        if (! empty($request->file('image'))) {
            //delete existing image from public folder
            //$destinationPath = env('BASE_PATH') . env('COMPANY_PATH');
            $destinationPath = env('COMPANY_PATH');
            //dd($destinationPath);
            // var_dump($destinationPath);die;
            if (! is_null($request->old_image) && file_exists($destinationPath . '/' . $request->old_image)) {
                unlink($destinationPath . '/' . $request->old_image);
            }
            $request = $this->savedImage($request);
        }
        if (empty($request->image) && ! is_null($request->old_image)) {
            $request->image = $request->old_image;
        }
        return $this->companyDao->update($request, $company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Company $company
     * @return Object $company
     */
    public function delete($company)
    {
        return $this->companyDao->delete($company);
    }

    /**
     * Get total company count
     *
     * @return Integer
     */
    public function getTotalCompany()
    {
        return $this->companyDao->getTotalCompany();
    }

    /**
     * Get total active company count
     *
     * @return Integer
     */
    public function getTotalActiveCompany()
    {
        return $this->companyDao->getTotalActiveCompany();
    }

    /**
     * Get total expire company count
     *
     * @return Integer
     */
    public function getTotalExpireCompany()
    {
        return $this->companyDao->getTotalExpireCompany();
    }

    /**
     * Save company images into D:\POS_Images from request
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Request  $request
     */
    private function savedImage($request)
    {
        $image = $request->file('image');
        $name = $request->name . '.' . $image->getClientOriginalExtension();
        //$destinationPath = env('BASE_PATH') . env('COMPANY_PATH');
        $destinationPath = env('COMPANY_PATH');
        
        $img = Image::make($image->path());
        $img->resize(120, 120, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $name);
        $request->image = $name;
        return $request;
    }
}
