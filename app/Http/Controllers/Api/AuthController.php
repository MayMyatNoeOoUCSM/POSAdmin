<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\StaffServiceInterface;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Session;

class AuthController extends Controller
{
    private $staffService;

    /**
     * Create a new controller instance.
     *
     * @param StaffServiceInterface $staffService
     * @return void
     */
    public function __construct(StaffServiceInterface $staffService)
    {
        $this->staffService = $staffService;
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
            return response()->json([
                'message' => __('message.E0002')
            ], 405);
        }
        $credentials = $request->only('staff_number', 'password');
        if (! Auth::guard('staff')->attempt($credentials)) {
            return response()->json([
                'message' => __('message.E00013')
            ], 401);
        }
        // return 1 company license active
        if (checkCompanyLicenseIsActive()==1) {
            // revoke previous all tokens for this users, user login once time.
            $tokenAll = $staff->tokens;
            foreach ($tokenAll as $token) {
                $token->revoke();
            }
            $token =$staff->createToken('Staff Token')->accessToken;
            return response()->json(['success' => true,'token'=>$token,'role_id'=>$staff->role], 200);
        }
        Auth::guard('staff')->logout();
        return response()->json(['status' => 'error','message'=>__('message.E00011')], 403);
    }
}
