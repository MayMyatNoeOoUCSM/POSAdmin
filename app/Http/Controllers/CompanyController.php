<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CompanyServiceInterface;
use App\Contracts\Services\StaffServiceInterface;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    private $companyService;
    private $staffService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\StaffServiceInterface $staffService
     * @param \App\Contracts\Services\CompanyServiceInterface $companyService
     *
     * @return void
     */
    public function __construct(StaffServiceInterface $staffService, CompanyServiceInterface $companyService)
    {
        $this->companyService = $companyService;
        $this->staffService = $staffService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companyList = $this->companyService->getCompanyList($request);
        if (is_null($companyList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('company.index', compact('companyList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $result = $this->companyService->insert($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Company']));
            return redirect()->route('company');
        }
        if (! empty($request->image) && file_exists($request->image)) {
            unlink($request->image);
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Request\CompanyRequest $request
     * @param  \App\Models\Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, Company $company)
    {
        //update company info in storage and check return statement
        $result = $this->companyService->update($request, $company);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'Company']));
            return redirect()->route('company');
        }
        //if company profile image exists, remove image from storage
        if (! empty($request->image) && file_exists(public_path($request->image))) {
            unlink(public_path($request->image));
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Company $company
     * @return \Illuminate\Http\Response
     */
    public function delete(Company $company)
    {
        //remove company from storage and check return statement
        $company = $this->companyService->delete($company);
        if ($company) {
            return redirect()->route('company')->with('success_status', __('message.I0003', ["tbl_name" => 'Company']));
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Loing By Company Admin
     * @param  Integer $id [company_id]
     * @return Company Admin Dashboard or Company List
     */
    public function loginByCompanyAdmin($id)
    {
        $loginStatus = $this->staffService->getStaffInfoByCompanyID($id);
        if ($loginStatus) {
            return redirect()->to('dashboard');
        }
        return redirect()->route('company')->with('error_status', __('message.E00012'));
    }
}
