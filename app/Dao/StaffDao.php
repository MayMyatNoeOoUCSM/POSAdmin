<?php

namespace App\Dao;

use App\Contracts\Dao\StaffDaoInterface;
use App\Models\Staff;

/**
 * Staff Dao
 *
 * @author
 */
class StaffDao implements StaffDaoInterface
{

    /**
     * Get latest staffNo for all role
     *
     *
     * @return Array
     */
    public function getLastStaffNoForAllRole()
    {
        $staff_all_role = [];
        $admin = Staff::where([
            ['role', 'LIKE', '%' . config('constants.ADMIN') . '%'],
        ])->orderBy('create_datetime', 'desc')->get();
        $admin = $admin->first();

        $company_admin = Staff::where([
            ['role', 'LIKE', '%' . config('constants.COMPANY_ADMIN') . '%'],
        ])->orderBy('create_datetime', 'desc')->get();
        $company_admin = $company_admin->first();
        //dd($company_admin->first());
        // $warehouse_staff = Staff::where([
        //     ['role', 'LIKE', '%' . config('constants.WAREHOUSE_STAFF') . '%'],
        // ])->orderBy('create_datetime', 'desc')->first();

        $shop_admin = Staff::where([
            ['role', 'LIKE', '%' . config('constants.SHOP_ADMIN') . '%'],
        ])->orderBy('create_datetime', 'desc')->get();
        $shop_admin = $shop_admin->first();

        $cashier_staff = Staff::where([
            ['role', 'LIKE', '%' . config('constants.CASHIER_STAFF') . '%'],
        ])->orderBy('create_datetime', 'desc')->get();
        $cashier_staff = $cashier_staff->first();

        $kitchen_staff = Staff::where([
            ['role', 'LIKE', '%' . config('constants.KITCHEN_STAFF') . '%'],
        ])->orderBy('create_datetime', 'desc')->get();
        $kitchen_staff = $kitchen_staff->first();

        $waiter_staff = Staff::where([
            ['role', 'LIKE', '%' . config('constants.WAITER_STAFF') . '%'],
        ])->orderBy('create_datetime', 'desc')->get();
        $waiter_staff = $waiter_staff->first();

        $sale_staff = Staff::where([
            ['role', 'LIKE', '%' . config('constants.SALE_STAFF') . '%'],
        ])->orderBy('create_datetime', 'desc')->get();
        $sale_staff = $sale_staff->first();

        array_push($staff_all_role, $admin);
        array_push($staff_all_role, $company_admin);
        array_push($staff_all_role, $shop_admin);
        //array_push($staff_all_role, $warehouse_staff);
        array_push($staff_all_role, $cashier_staff);
        array_push($staff_all_role, $kitchen_staff);
        array_push($staff_all_role, $waiter_staff);
        array_push($staff_all_role, $sale_staff);
        return $staff_all_role;
    }

    /**
     * Get staff info search by staff no from storage
     *
     * @param String $staff_no
     * @return Object $staff
     */
    public function getStaffByStaffNo($staff_no)
    {
        $staff = Staff::where([
            ['staff_number', $staff_no],
            ['staff_status', config('constants.ACTIVE')],
        ])->first();
        return $staff;
    }

    /**
     * Get staff no lists from storage
     *
     * @return Object $staffNo
     */
    public function getStaffNo()
    {
        $staffNo = Staff::select('staff_number')->where('staff_status', '!=', config('constants.DELETED'))->get();
        return $staffNo;
    }

    /**
     * Get staff list for export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $staffList
     */
    public function getStaffListForExport($request)
    {
        $staffList = Staff::select(
            'staff_number',
            'role',
            'staff_type',
            'position',
            'bank_account_number',
            'graduated_univeristy',
            'name',
            'gender',
            'nrc_number',
            'dob',
            'marital_status',
            'race',
            'city',
            'address',
            'phone_number_1',
            'phone_number_2',
            'join_from',
            'join_to',
            'staff_status'
        )
            ->where('staff_status', '!=', config('constants.DELETED'));
        if (! empty($request->search_name)) {
            $staffList = $staffList->where('m_staff.name', 'like', '%' . $request->search_name . '%');
        }
        if (! empty($request->search_no)) {
            $staffList = $staffList->where('m_staff.staff_number', $request->search_no);
        }
        if (! empty($request->search_join_from)) {
            $staffList = $staffList->where('m_staff.join_from', $request->search_join_from);
        }
        if (! empty($request->search_role)) {
            $staffList = $staffList->where('m_staff.role', $request->search_role);
        }
        if (! empty($request->search_type)) {
            $staffList = $staffList->where('m_staff.staff_type', $request->search_type);
        }
        if (! empty($request->search_position)) {
            $staffList = $staffList->where('m_staff.position', $request->search_position);
        }
        $staffList = $staffList->get();
        return $staffList;
    }

    /**
     * Get staff list from storage
     *
     * @param \Illumite\Http\Request $request
     * @return Object $staffList
     */
    public function getStaffList($request)
    {
        $staffList = Staff::leftJoin('m_warehouse as w', 'w.id', '=', 'm_staff.warehouse_id')
            ->leftJoin('m_shop as s', 's.id', '=', 'm_staff.shop_id')
            ->leftJoin('m_company as c', 'c.id', '=', 'm_staff.company_id')
            ->where('staff_status', '!=', config('constants.DELETED'));
        if (! empty($request->search_name)) {
            $staffList = $staffList->whereRaw("LOWER(m_staff.name) like LOWER('%" . $request->search_name . "%')");
        }

        if (! empty($request->search_no)) {
            $staffList = $staffList->where('m_staff.staff_number', $request->search_no);
        }
        if (! empty($request->search_join_from)) {
            $staffList = $staffList->where('m_staff.join_from', $request->search_join_from);
        }
        if (! empty($request->search_role)) {
            $staffList = $staffList->where('m_staff.role', $request->search_role);
        }
        if (! empty($request->search_type)) {
            $staffList = $staffList->where('m_staff.staff_type', $request->search_type);
        }
        if (! empty($request->search_position)) {
            $staffList = $staffList->where('m_staff.position', $request->search_position);
        }
        $staffList = $staffList->select('m_staff.*', 'w.name as warehouse_name', 's.name as shop_name', 'c.name as company_name');
        $staffList = $staffList->paginate($request->custom_pg_size == "" ? config('constants.STAFF_PAGINATION') : $request->custom_pg_size);
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
        $staff = new Staff;
        $staff->name = $request->name;
        $staff->staff_number = $request->staff_number;
        $staff->password = bcrypt($request->password);
        $staff->role = $request->role;
        $staff->staff_type = $request->type;
        $staff->position = $request->position;
        $staff->bank_account_number = $request->bank_acc_no;
        $staff->graduated_univeristy = $request->graduated_univeristy;
        $staff->gender = $request->gender;
        $staff->nrc_number = $request->nrc_number;
        $staff->dob = $request->dob;
        $staff->marital_status = $request->marital_status;
        $staff->race = $request->race;
        $staff->city = $request->city;
        $staff->address = $request->address;
        $staff->photo = $request->image;
        $staff->phone_number_1 = $request->ph_no1;
        $staff->phone_number_2 = $request->ph_no2;
        $staff->join_from = $request->join_from;
        $staff->join_to = $request->join_to;
        $staff->company_id = $request->company_id;
        $staff->warehouse_id = $request->warehouse_id;
        $staff->shop_id = $request->shop_id;
        if ($request->shop_id !="") {
            $staff->company_id = \App\Models\Shop::where('id', $request->shop_id)->first()->company_id;
        }
        $staff->staff_status = $request->active ? config('constants.ACTIVE') : config('constants.IN_ACTIVE');
        $staff->create_user_id = auth()->user()->id;
        $staff->update_user_id = auth()->user()->id;
        $staff->create_datetime = Date('Y-m-d H:i:s');
        $staff->update_datetime = Date('Y-m-d H:i:s');
        $staff->save();
        return $staff;
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
        $id = $staff->id;

        if ((int) $request->role != $staff->role) {
            $staff = new Staff;
            $staff->role = (int) $request->role;
            $staff->staff_number = $request->staff_number;
            $staff->old_id = $id;
            $staff->create_user_id = auth()->user()->id;
            $staff->create_datetime = Date('Y-m-d H:i:s');
            Staff::where('id', "=", $id)->update(['staff_status' => config("constants.DELETED")]);
        }
        if ((int) $request->role == config('constants.COMPANY_ADMIN')) {
            $staff->company_id = $request->company_id;
        }
        if ((int) $request->role == config('constants.SHOP_ADMIN')
            or (int) $request->role == config('constants.CASHIER_STAFF')
            or (int) $request->role == config('constants.KITCHEN_STAFF')
            or (int) $request->role == config('constants.WAITER_STAFF')
            or (int) $request->role == config('constants.SALE_STAFF')) {
            $staff->shop_id = $request->shop_id;
            $staff->company_id = \App\Models\Shop::where('id', $request->shop_id)->first()->company_id;
        }
        //var_dump($request->warehouse_id. '/'.$request->shop_id);die;

        $staff->name = $request->name;
        $staff->staff_type = $request->type;
        $staff->position = $request->position;
        $staff->bank_account_number = $request->bank_acc_no;
        $staff->graduated_univeristy = $request->graduated_univeristy;
        $staff->gender = $request->gender;
        $staff->nrc_number = $request->nrc_number;
        $staff->dob = $request->dob;
        $staff->marital_status = $request->marital_status;
        $staff->race = $request->race;
        $staff->city = $request->city;
        $staff->address = $request->address;
        $staff->photo = $request->image;
        $staff->phone_number_1 = $request->ph_no1;
        $staff->phone_number_2 = $request->ph_no2;
        $staff->join_from = $request->join_from;
        $staff->join_to = $request->join_to;
        $staff->staff_status = $request->active ? config('constants.ACTIVE') : config('constants.IN_ACTIVE');
        $staff->update_user_id = auth()->user()->id;
        $staff->update_datetime = Date('Y-m-d H:i:s');
        $staff->save();
        return $staff;
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
        $staff = Staff::where('id', $staff->id)->update(['staff_status' => config('constants.DELETED')]);
        return $staff;
    }

    /**
     * Get staff info search by company id in storage
     *
     * @param Integer $company_id
     * @param  \App\Models\Staff $staff
     * @return Object $staff
     */
    public function getStaffInfoByCompanyID($company_id)
    {
        $staff = Staff::where('company_id', $company_id)
            ->where('role', config('constants.COMPANY_ADMIN'))
            ->first();
        return $staff;
    }

    /**
     * Get staff info search by staff id from storage
     *
     * @param Integer $staff_id
     * @return Object $staff
     */
    public function getStaffDetails($staff_id)
    {
        $staff = Staff::where([
            ['id', $staff_id],
            ['staff_status', config('constants.ACTIVE')],
        ])->first();
        return $staff;
    }
    /**
     * Get Staff List By Shop ID Arra
     *
     * @param  Array $shop_id_array
     * @return Object
     */
    public function getStaffListByShopIDArray($shop_id_array)
    {
        $staff = Staff::select('m_staff.id')
            ->whereIn('shop_id', $shop_id_array)
            ->where('role', config('constants.SHOP_ADMIN'))
            ->where('staff_status', '!=', config('constants.DELETED'))
            ->get();
        return $staff;
    }
}
