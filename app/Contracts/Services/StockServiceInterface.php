<?php

namespace App\Contracts\Services;

interface StockServiceInterface
{

    /**
     * Get Current Stock List
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getCurrentStockList($request);

    /**
     * Store stock info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $stock
     */
    public function insert($request);

    /**
     * Get warehouse shop product table info
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getwarehouseShopProduct($request);

    /**
     * Get stock list search by warehouse or product
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getWarehouseStockList($request);

    /**
     * Get transfering stock to warehouse from warehouse or shop
     *
     * @param \App\Http\Requests\StockTransferRequest $request
     * @return Boolean
     */
    public function stockTransfer($request);

    /**
     * Get low stock info
     *
     * @return Object
     */
    public function lowStockAlert();
}
