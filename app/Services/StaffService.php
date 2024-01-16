<?php

namespace App\Services;

use App\Contracts\Dao\StaffDaoInterface;
use App\Contracts\Services\StaffServiceInterface;
use Illuminate\Support\Facades\Auth;
use Image;

class StaffService implements StaffServiceInterface
{
    private $staffDao;

    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\StaffDaoInterface $staffDao
     * @return void
     */
    public function __construct(StaffDaoInterface $staffDao)
    {
        $this->staffDao = $staffDao;
    }

    /**
     * Get latest staffNo for all role
     *
     *
     * @return Array
     */
    public function getLastStaffNoForAllRole()
    {
        $staff_number_all_role = [];
        $staff_number_start_char = ['A', 'CA', 'SA', 'CS', 'KS', 'WS', 'SS'];
        $staff_all_role = $this->staffDao->getLastStaffNoForAllRole();
        for ($i = 0; $i < 7; $i++) {
            if (! is_null($staff_all_role[$i])) {
                $staff_number = substr($staff_all_role[$i]->staff_number, -3) + 1;
                $staff_number = str_pad($staff_number, 3, '0', STR_PAD_LEFT);
                array_push($staff_number_all_role, $staff_number_start_char[$i] . $staff_number);
            } else {
                array_push($staff_number_all_role, $staff_number_start_char[$i] . '001');
            }
        }
        return $staff_number_all_role;
    }

    /**
     * Get staff info search by staff no from storage
     *
     * @param String $staff_no
     * @return Object $staff
     */
    public function getStaffByStaffNo($staff_no)
    {
        return $this->staffDao->getStaffByStaffNo($staff_no);
    }

    /**
     * Get staff no lists from storage
     *
     * @return Object $staffNo
     */
    public function getStaffNo()
    {
        return $this->staffDao->getStaffNo();
    }

    /**
     * Get staff list from storage
     *
     * @param \Illumite\Http\Request $request
     * @return Object $staffList
     */
    public function getStaffList($request)
    {
        $staffList = $this->staffDao->getStaffList($request);
        return $staffList;
    }

    /**
     * Get staff list for export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $staffList
     */
    public function getStaffListForExport($request)
    {
        $staffList = $this->staffDao->getStaffListForExport($request);
        for ($i = 0; $i < count($staffList); $i++) {
            if ($staffList[$i]->role == config('constants.ADMIN')) {
                $staffList[$i]->role = "Admin";
            } elseif ($staffList[$i]->role == config('constants.COMPANY_ADMIN')) {
                $staffList[$i]->role = "Company Admin";
            } elseif ($staffList[$i]->role == config('constants.SHOP_ADMIN')) {
                $staffList[$i]->role = "Shop Admin";
            } elseif ($staffList[$i]->role == config('constants.CASHIER_STAFF')) {
                $staffList[$i]->role = "Cashier Staff";
            } elseif ($staffList[$i]->role == config('constants.KITCHEN_STAFF')) {
                $staffList[$i]->role = "Kitchen Staff";
            } elseif ($staffList[$i]->role == config('constants.WAITER_STAFF')) {
                $staffList[$i]->role = "Waiter Staff";
            } else {
                $staffList[$i]->role = "Sale Staff";
            }

            if ($staffList[$i]->staff_type == config('constants.FULL_TIME')) {
                $staffList[$i]->staff_type = "Full Time";
            } else {
                $staffList[$i]->staff_type = "Part Time";
            }

            if ($staffList[$i]->position == config('constants.SYSTEM_ADMIN')) {
                $staffList[$i]->position = "System Admin";
            } elseif ($staffList[$i]->position == config('constants.OWNER')) {
                $staffList[$i]->position = "Owner";
            } elseif ($staffList[$i]->position == config('constants.MANAGER')) {
                $staffList[$i]->position = "Manager";
            } else {
                $staffList[$i]->position = "Operation Staff";
            }

            if ($staffList[$i]->marital_status == config('constants.SINGLE')) {
                $staffList[$i]->marital_status = "Single";
            } else {
                $staffList[$i]->marital_status = "Married";
            }

            if ($staffList[$i]->gender == config('constants.MALE')) {
                $staffList[$i]->gender = "Male";
            } elseif ($staffList[$i]->gender == config('constants.FEMALE')) {
                $staffList[$i]->gender = "Female";
            } else {
                $staffList[$i]->gender = "Other";
            }

            if ($staffList[$i]->staff_status == config('constants.ACTIVE')) {
                $staffList[$i]->staff_status = "Active";
            } else {
                $staffList[$i]->staff_status = "Inactive";
            }
        }
        return $staffList;
    }

    /**
     * Store staff info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $staff
     */
    public function insert($request)
    {
        if (! empty($request->image)) {
            $request = $this->savedImage($request);
        }
        return $this->staffDao->insert($request);
    }

    /**
     * Update staff info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Staff $staff
     * @return Object $staff
     */
    public function update($request, $staff)
    {
        if (! empty($request->image)) {
            //delete existing image from public folder
            $destinationPath = env('STAFF_PATH');
            var_dump($request->old_image);
            //die;
            if (! is_null($request->old_image) && file_exists($destinationPath . '/' . $request->old_image)) {
                unlink($destinationPath . '/' . $request->old_image);
            }
            $request = $this->savedImage($request);
        }
        if (empty($request->image) && ! is_null($request->old_image)) {
            $request->image = $request->old_image;
        }
        return $this->staffDao->update($request, $staff);
    }

    /**
     * Remove staff info in storage
     *
     * @param \App\Models\Staff $staff
     * @return Object $staff
     *
     */
    public function delete($staff)
    {
        return $this->staffDao->delete($staff);
    }

    /**
     * Get staff info search by company id in storage
     *
     * @param Integer $company_id
     * @param  \App\Models\Staff $staff
     * @return Boolean
     */
    public function getStaffInfoByCompanyID($company_id)
    {
        $staff = $this->staffDao->getStaffInfoByCompanyID($company_id);
        if ($staff) {
            Auth::logout(); // for end current session
            Auth::loginUsingId($staff->id);
            return true;
        }
        return false;
    }

    /**
     * Get staff info search by staff id from storage
     *
     * @param Integer $staff_id
     * @return Object $staff
     */
    public function getStaffDetails($staff_id)
    {
        return $this->staffDao->getStaffDetails($staff_id);
    }

    /**
     * Save staff images into D:\POS_Images from request
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Request  $request
     */
    private function savedImage($request)
    {
        $image = $request->file('image');
        $name = $request->staff_number . '.' . $image->getClientOriginalExtension();
        //$destinationPath = env('BASE_PATH') . env('STAFF_PATH');
        $destinationPath = env('STAFF_PATH');
        $img = Image::make($image->path());
        $img->resize(120, 120, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $name);
        $request->image = $name;
        return $request;
    }
}
