<?php

namespace App\Contracts\Services;

interface WarehouseServiceInterface
{

    /**
     * Get warehouse no by calculating
     *
     * @return String
     */
    public function getWarehouseCode();

    /**
     * Get warehouse list from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $warehouseList
     */
    public function getWarehouseList($request);

    /**
     * Get all warehouse list from storage
     *
     * @return Object $warehouseList
     */
    public function getAllWarehouseList();

    /**
     * Store warehouse info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $warehouse
     */
    public function insert($request);

    /**
     * Update warehouse info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Warehouse $warehouse
     * @return Object $warehouse
     */
    public function update($request, $warehouse);

    /**
     * Remove specified warehouse info from storage
     *
     * @param \App\Models\Warehouse $warehouse
     * @return Object $warehouse
     */
    public function delete($warehouse);
}
