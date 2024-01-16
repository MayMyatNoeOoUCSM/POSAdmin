<?php

namespace App\Contracts\Dao;

/**
 * StaffDaoInterface
 */
interface StaffDaoInterface
{

    /**
     * Get Latest StaffNo For All Role
     *
     * @return Object $staffList
     */
    public function getLastStaffNoForAllRole();

    /**
     * Get staff by staff no
     *
     * @param Integer $staff_no
     * @return Object Staff
     */
    public function getStaffByStaffNo($staff_no);

    /**
     * Get Staff No
     *
     * @return String staffNo
     */
    public function getStaffNo();

    /**
     * Get Staff List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object staffList
     */
    public function getStaffList($request);

    /**
     * Get Staff List For Export from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object staffList
     */
    public function getStaffListForExport($request);

    /**
     * Staff data is saved into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $staff
     */
    public function insert($request);

    /**
     * Staff data is update into storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \app\Models\Staff $staff
     * @return Object $staff
     */
    public function update($request, $staff);

    /**
     * Delete User By Id from storage
     *
     * @param \app\Models\Staff $staff
     * @return Object $staff
     */
    public function delete($staff);

    /**
     * Get staff info search by staff id from storage
     *
     * @param Integer $staff_id
     * @return Object $staff
     */
    public function getStaffDetails($staff_id);

    /**
    * Get Staff List By Shop ID Arra
    *
    * @param  Array $shop_id_array
    * @return Object
    */
    public function getStaffListByShopIDArray($shop_id_array);
}
