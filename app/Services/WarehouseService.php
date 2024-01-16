<?php

namespace App\Services;

use App\Contracts\Dao\WarehouseDaoInterface;
use App\Contracts\Services\WarehouseServiceInterface;

class WarehouseService implements WarehouseServiceInterface
{
    private $warehouseDao;

    /**
     * Class Constructor
     * @param \App\Contracts\Dao\WarehouseDaoInterface $warehouseDao
     * @return
     */
    public function __construct(WarehouseDaoInterface $warehouseDao)
    {
        $this->warehouseDao = $warehouseDao;
    }

    /**
     * Get warehouse no by calculating
     *
     * @return String
     */
    public function getWarehouseCode()
    {
        $warehouseCode = $this->warehouseDao->getWarehouseCode();

        if (! is_null($warehouseCode)) {
            $warehouse_code = substr($warehouseCode->name, -3) + 1;
            $warehouse_code = 'W' . str_pad($warehouse_code, 3, '0', STR_PAD_LEFT);
        } else {
            $warehouse_code = 'W001';
        }
        return $warehouse_code;
    }

    /**
     * Get warehouse list from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $warehouseList
     */
    public function getWarehouseList($request)
    {
        return $this->warehouseDao->getWarehouseList($request);
    }

    /**
     * Get all warehouse list from storage
     *
     * @return Object $warehouseList
     */
    public function getAllWarehouseList()
    {
        return $this->warehouseDao->getAllWarehouseList();
    }

    /**
     * Store warehouse info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $warehouse
     */
    public function insert($request)
    {
        return $this->warehouseDao->insert($request);
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
        return $this->warehouseDao->update($request, $warehouse);
    }

    /**
     * Remove specified warehouse info from storage
     *
     * @param \App\Models\Warehouse $warehouse
     * @return Object $warehouse
     */
    public function delete($warehouse)
    {
        return $this->warehouseDao->delete($warehouse);
    }
}
