<?php

namespace App\Http\Controllers;

use App\Contracts\Services\StaffServiceInterface;
use Auth;
use Illuminate\Http\Request;
use Session;

class AuthController extends Controller
{
    private $staffService;

    /**
     * Create a new controller instance.
     * @param StaffServiceInterface $staffService
     *
     * @return void
     */
    public function __construct(StaffServiceInterface $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * Show login form
     *
     * @return void
     */
    public function getLogin()
    {
        return view('staff.login');
    }

    /**
     * Login
     *
     * @param Request $request
     * @return void
     */
    public function postLogin(Request $request)
    {
        $staff = $this->staffService->getStaffByStaffNo($request->staff_number);

        if (empty($staff)) {
            return redirect()->back()->withInput()->with('error_status', __('message.E0002'));
        } elseif ($staff == "db_error") {
            return redirect()->back()->withInput()->with('error_status', __('message.E0001'));
        }
        if ($staff->role== config('constants.CASHIER_STAFF')) {
            return redirect()->back()->withInput()->with('error_status', __('message.E0004'));
        } elseif ($staff->role == config('constants.KITCHEN_STAFF')) {
            return redirect()->back()->withInput()->with('error_status', __('message.E0005'));
        } elseif ($staff->role == config('constants.WAITER_STAFF')) {
            return redirect()->back()->withInput()->with('error_status', __('message.E0006'));
        } elseif ($staff->role == config('constants.SALE_STAFF')) {
            return redirect()->back()->withInput()->with('error_status', __('message.E00010'));
        }
        $credentials = $request->only('staff_number', 'password');
        if (Auth::guard('staff')->attempt($credentials)) {
            // return 1 company license active
            if (checkCompanyLicenseIsActive()==1) {
                return redirect()->route('dashboard');
            }
            // return 2 company license inactive,expired,block
            Session::put('locale', null);
            Auth::guard('staff')->logout();
            return redirect()->back()->withInput()->with('error_status', __('message.E00011'));
        }
        return redirect()->back()->withInput()->with('error_status', __('message.E0003'));
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout(Request $request)
    {
        Session::put('locale', null);
        Auth::guard('staff')->logout();

        // return redirect(\URL::previous());
        //return redirect(\URL::previous());
        return redirect()->route('login.getLogin');
    }
}
