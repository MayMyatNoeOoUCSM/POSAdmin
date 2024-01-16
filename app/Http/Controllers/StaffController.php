<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CompanyServiceInterface;
use App\Contracts\Services\ShopServiceInterface;
use App\Contracts\Services\StaffServiceInterface;
use App\Contracts\Services\WarehouseServiceInterface;
use App\Exports\StaffsExport;
use App\Http\Requests\StaffRequest;
use App\Models\Staff;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StaffController extends Controller
{
    private $staffService;
    private $companyService;
    private $shopService;
    private $warehouseService;
    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\StaffServiceInterface $staffService
     * @param \App\Contracts\Services\CompanyServiceInterface $companyService
     * @param \App\Contracts\Services\ShopServiceInterface $shopService
     * @return void
     */
    public function __construct(StaffServiceInterface $staffService, CompanyServiceInterface $companyService, ShopServiceInterface $shopService, WarehouseServiceInterface $warehouseService)
    {
        $this->staffService = $staffService;
        $this->companyService = $companyService;
        $this->shopService = $shopService;
        $this->warehouseService = $warehouseService;
    }

    /**
     *
     * Show Staff List Form
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //to export staff data with condition
        if (! is_null($request->download)) {
            return Excel::download(new StaffsExport($this->staffService, $request), 'staffs.xlsx');
        }
        // initial display and search button click
        $staffNos = $this->staffService->getStaffNo();
        $staffList = $this->staffService->getStaffList($request);

        if (is_null($staffList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('staff.index', compact('staffList', 'staffNos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //staff_no_all_role, warehouse list and shop list to create new staff
        $staff_no_all_role = $this->staffService->getLastStaffNoForAllRole();
        $companyList = $this->companyService->getAllCompanyList();
        $shopList = $this->shopService->getAllShopList();
        return view('staff.create', compact('staff_no_all_role', 'companyList', 'shopList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StaffRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaffRequest $request)
    {
        //check password format to create new staff
        if (($request->role == 1 || $request->role == 2 || $request->role == 4)) {
            if (empty($request->password)) {
                return redirect()->back()->with('error_status', __('message.E0007'))->withInput();
            } elseif (! preg_match('/[A-Z]/', $request->password) || ! preg_match('/[\d]/', $request->password)) {
                return back()->with('error_status', __('message.E0008'))->withInput();
            }
        }
        //store new staff info in storage and check return statement
        $result = $this->staffService->insert($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Staff']));
            return redirect()->route('staff');
        }
        //if staff profile image exists, remove image from storage
        if (! empty($request->image) && file_exists($request->image)) {
            unlink($request->image);
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     *Show edit form for staff
     *
     * @param \App\Models\Staff $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        //staff_no_all_role, warehouselist and shoplist to update staff info in storage
        $staff_no_all_role = $this->staffService->getLastStaffNoForAllRole();
        $companyList = $this->companyService->getAllCompanyList();
        $shopList = $this->shopService->getAllShopList();
        // if (!empty($staff->photo)) {
        //     $info = pathinfo($staff->photo);
        //     $staff->photo = config('constants.STAFF_PATH') . "/" . $info['basename'];
        // }
        return view('staff.edit', compact('staff', 'staff_no_all_role', 'companyList', 'shopList'));
    }

    /**
     *
     * Update the specified resource in storage
     *
     * @param  \App\Htpp\Requests\StaffRequest  $request
     * @param  \App\Models\Staff $staff
     * @return \Illuminate\Http\Response
     */
    public function update(StaffRequest $request, Staff $staff)
    {
        //update staff info in storage and check return statement
        $result = $this->staffService->update($request, $staff);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'Staff']));
            return redirect()->route('staff');
        }
        //if staff profile image exists, remove image from storage
        if (! empty($request->image) && file_exists(public_path($request->image))) {
            unlink(public_path($request->image));
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Staff $staff
     * @return \Illuminate\Http\Response
     */
    public function delete(Staff $staff)
    {
        //remove staff from storage and check return statement
        $staff = $this->staffService->delete($staff);
        if ($staff) {
            return redirect()->route('staff')->with('success_status', __('message.I0003', ["tbl_name" => 'Staff']));
        }
        return back()->with('error_status', __('message.E0001'));
    }
}
