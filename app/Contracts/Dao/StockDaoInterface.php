<?php

namespace App\Contracts\Dao;

/**
 * StockDaoInterface
 */
interface StockDaoInterface
{

    /**
     * Get Current Stock List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $currentStockList
     */
    public function getCurrentStockList($request);

    /**
     * Stock data is saved into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $stock
     */
    public function insert($request);

    /**
     * Get data that MinQty is greater than and equal with Qty
     *
     * @return Object $lowStock
     */
    public function getLowStock();

    /**
     * Get WarehouseShopProduct
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $warehouseShopProduct
     */
    public function getwarehouseShopProduct($request);

    /**
     * Get Warehouse List
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $warehouseStockList
     */
    public function getWarehouseStockList($request);

    /**
     * Stock Transfer from warehouse to other
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function stockTransfer($request);

    /**
     * Lowest Stock Alert Info
     *
     * @return Object
     */
    public function lowStockAlert();
}
