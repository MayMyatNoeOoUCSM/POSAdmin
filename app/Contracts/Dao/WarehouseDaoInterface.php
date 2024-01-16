<?php

namespace App\Contracts\Dao;

/**
 * WarehouseDaoInterface
 */
interface WarehouseDaoInterface
{

    /**
     * Get Warehouse No from storage
     *
     * @return String warehouseCode
     */
    public function getWarehouseCode();

    /**
     * Get Warehouse from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object warehouseList
     */
    public function getWarehouseList($request);

    /**
     * Get Warehouse from storage
     *
     * @return Object warehouseList
     */
    public function getAllWarehouseList();

    /**
     * Warehouse data is saved into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $warehouse
     */
    public function insert($request);

    /**
     * Update Warehouse data into storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \app\Models\Warehouse $warehouse
     * @return Object $warehouse
     */
    public function update($request, $warehouse);

    /**
     * Delete Warehouse By Id from storage
     *
     * @param \app\Models\Warehouse $warehouse
     *
     * @return Object $warehouse
     */
    public function delete($warehouse);

    /**
     * Get warehouse details info search by warehouse id
     *
     * @param  Integer $warehouse_id
     * @return Object
     */
    public function details($warehouse_id);

    /**
    * Get warehouse list by company id
    *
    * @param  Integer company_id
    * @return Object
    */
    public function getWarehouseByCompanyID($company_id);
}
