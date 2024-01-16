<?php

namespace App\Dao;

use App\Contracts\Dao\WarehouseDaoInterface;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

/**
 * Warehouse Dao
 *
 * @author
 */
class WarehouseDao implements WarehouseDaoInterface
{
    /**
     * Get warehouse no from storage
     *
     * @return Object $warehouseCode
     */
    public function getWarehouseCode()
    {
        $warehouseCode = Warehouse::select('name')->orderBy('create_datetime', 'desc')->first();
        return $warehouseCode;
    }

    /**
     * Get warehouse list from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $warehouseList
     */
    public function getWarehouseList($request)
    {
        $warehouseList = Warehouse::where('is_deleted', config('constants.DEL_FLG_OFF'));
        $warehouseList = $warehouseList->paginate($request->custom_pg_size == "" ? config('constants.WAREHOUSE_PAGINATION') : $request->custom_pg_size);
        return $warehouseList;
    }

    /**
     * Get all warehouse list from storage
     *
     * @return Object $warehouseList
     */
    public function getAllWarehouseList()
    {
        $warehouseList = Warehouse::where('is_deleted', config('constants.DEL_FLG_OFF'))->get();
        return $warehouseList;
    }

    /**
     * Store warehouse info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $warehouse
     */
    public function insert($request)
    {
        $warehouse = new Warehouse;
        $warehouse->company_id = 1;
        $warehouse->name = $request->name;
        $warehouse->address = $request->address;
        $warehouse->phone_number_1 = $request->phone_number_1;
        $warehouse->phone_number_2 = $request->phone_number_2;
        $warehouse->is_deleted = config('constants.DEL_FLG_OFF');
        $warehouse->create_user_id = auth()->user()->id;
        $warehouse->update_user_id = auth()->user()->id;
        $warehouse->create_datetime = Date('Y-m-d H:i:s');
        $warehouse->update_datetime = Date('Y-m-d H:i:s');
        $warehouse->save();
        return $warehouse;
    }

    /**
     * Update warehouse info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Warehouse $warehouse
     * @return Object $warehouse
     */
    public function update($request, $warehouse)
    {
        $warehouse->name = $request->name;
        $warehouse->address = $request->address;
        $warehouse->phone_number_1 = $request->phone_number_1;
        $warehouse->phone_number_2 = $request->phone_number_2;
        $warehouse->update_user_id = auth()->user()->id;
        $warehouse->update_datetime = Date('Y-m-d H:i:s');
        $warehouse->save();
        return $warehouse;
    }

    /**
     * Remove specified warehouse info from storage
     *
     * @param \App\Models\Warehouse $warehouse
     * @return Object $warehouse
     */
    public function delete($warehouse)
    {
        $stockExistCheck = DB::table('t_stock')->where('warehouse_id', $warehouse->id)->first();
        if ($stockExistCheck) {
            return 1;
        }

        $warehouse = Warehouse::where('id', $warehouse->id)->update(['is_deleted' => config('constants.DEL_FLG_ON')]);
        return 2;
    }

    /**
     * Get warehouse details info search by warehouse id
     *
     * @param  Integer $warehouse_id
     * @return Object
     */
    public function details($warehouse_id)
    {
        return Warehouse::find($warehouse_id);
    }

    /**
     * Get warehouse list by company id
     *
     * @param  Integer company_id
     * @return Object
     */
    public function getWarehouseByCompanyID($company_id)
    {
        return Warehouse::where("company_id", $company_id)->get();
    }
}
