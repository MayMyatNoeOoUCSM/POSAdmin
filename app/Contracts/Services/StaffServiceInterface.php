<?php

namespace App\Contracts\Services;

interface StaffServiceInterface
{

    /**
     * Get latest staffNo for all role
     *
     *
     * @return Array
     */
    public function getLastStaffNoForAllRole();

    /**
     * Get staff info search by staff no from storage
     *
     * @param String $staff_no
     * @return Object $staff
     */
    public function getStaffByStaffNo($staff_no);

    /**
     * Get staff no lists from storage
     *
     * @return Object $staffNo
     */
    public function getStaffNo();

    /**
     * Get staff list from storage
     *
     * @param \Illumite\Http\Request $request
     * @return Object $staffList
     */
    public function getStaffList($request);

    /**
     * Get staff list for export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $staffList
     */
    public function getStaffListForExport($request);

    /**
     * Store staff info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $staff
     */
    public function insert($request);

    /**
     * Update staff info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Staff $staff
     * @return Object $staff
     */
    public function update($request, $staff);

    /**
     * Remove staff info in storage
     *
     * @param \App\Models\Staff $staff
     * @return Object $staff
     */
    public function delete($staff);

    /**
     * Get staff info search by company id in storage
     *
     * @param Integer $company_id
     * @param  \App\Models\Staff $staff
     * @return Boolean
     */
    public function getStaffInfoByCompanyID($company_id);

    /**
     * Get staff info search by staff id from storage
     *
     * @param Integer $staff_id
     * @return Object $staff
     */
    public function getStaffDetails($staff_id);
}
