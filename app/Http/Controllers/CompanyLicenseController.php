<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CompanyLicenseServiceInterface;
use App\Contracts\Services\CompanyServiceInterface;
use App\Http\Requests\CompanyLicenseRequest;
use App\Models\CompanyLicense;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class CompanyLicenseController extends Controller
{
    private $companyService;
    private $companyLicenseService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\CompanyService $companyService
     * @return void
     */
    public function __construct(CompanyServiceInterface $companyService, CompanyLicenseServiceInterface $companyLicenseService)
    {
        $this->companyService = $companyService;
        $this->companyLicenseService = $companyLicenseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companyList = $this->companyLicenseService->getCompanyLicenseList($request);
        if (is_null($companyList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('companylicense.index', compact('companyList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companyList = $this->companyService->getAllCompanyList();
        return view('companylicense.create', compact('companyList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CompanyLicenseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyLicenseRequest $request)
    {
        //store new company license info in storage and check return statement
        $result = $this->companyLicenseService->insert($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'CompanyLicense']));
            return redirect()->route('company.license');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyLicense $license
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyLicense $license)
    {
        $companyList = $this->companyService->getAllCompanyList();
        return view('companylicense.edit', compact('license', 'companyList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Requests\CompanyLicenseRequest  $request
     * @param  \App\Models\CompanyLicense $license
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyLicenseRequest $request, CompanyLicense $license)
    {
        //update company license info in storage and check return statement
        $result = $this->companyLicenseService->update($request, $license);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'CompanyLicense']));
            return redirect()->route('company.license');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CompanyLicense $license
     * @return \Illuminate\Http\Response
     */
    public function delete(CompanyLicense $license)
    {       //remove company from storage and check return statement
        $company = $this->companyLicenseService->delete($license);
        if ($company) {
            return redirect()->route('company.license')->with('success_status', __('message.I0003', ["tbl_name" => 'CompanyLicense']));
        }
        return back()->with('error_status', __('message.E0001'));
    }
}
