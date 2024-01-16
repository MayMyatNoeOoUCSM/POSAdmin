<?php

namespace App\Services;

use App\Contracts\Dao\ShopDaoInterface;
use App\Contracts\Dao\StockDaoInterface;
use App\Contracts\Dao\WarehouseDaoInterface;
use App\Contracts\Services\StockServiceInterface;
use Auth;

class StockService implements StockServiceInterface
{
    private $stockDao;
    private $shopDao;
    private $warehouseDao;

    /**
     * Class Constructor
     *
     * @param App\Contracts\Dao\StockDaoInterface $stockDao
     * @param App\Contracts\Dao\ShopDaoInterface $shopDao
     * @param App\Contracts\Dao\WarehouseDaoInterface $warehouseDao
     * @return void
     */
    public function __construct(StockDaoInterface $stockDao, ShopDaoInterface $shopDao, WarehouseDaoInterface $warehouseDao)
    {
        $this->stockDao = $stockDao;
        $this->shopDao  = $shopDao;
        $this->warehouseDao = $warehouseDao;
    }

    /**
     * Get Current Stock List
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getCurrentStockList($request)
    {
        return $this->stockDao->getCurrentStockList($request);
    }

    /**
     * Store stock info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return  Object $stock
     */
    public function insert($request)
    {
        // Check shop id is owned for company admin role
        if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
            if ($request->shop_id[0] != null) {
                $shopList = $this->shopDao->getShopTypeByCompanyID(Auth::guard('staff')->user()->company_id);
            
                foreach ($request->shop_id as $value) {
                    $checkExists=$shopList->contains('id', $value);
                    if (! $checkExists) {
                        return $checkExists;
                    }
                }
            }

            if ($request->warehouse_id[0] != null) {
                $warehouseList = $this->warehouseDao->getWarehouseByCompanyID(Auth::guard('staff')->user()->company_id);
                
                foreach ($request->warehouse_id as $value) {
                    $checkExists=$warehouseList->contains('id', $value);
                    if (! $checkExists) {
                        return $checkExists;
                    }
                }
            }
        }
        return $this->stockDao->insert($request);
    }

    /**
     * Get warehouse shop product table info
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getwarehouseShopProduct($request)
    {
        return $this->stockDao->getwarehouseShopProduct($request);
    }

    /**
     * Get stock list search by warehouse or product
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getWarehouseStockList($request)
    {
        return $this->stockDao->getWarehouseStockList($request);
    }

    /**
     * Get transfering stock to warehouse from warehouse or shop
     *
     * @param \App\Http\Requests\StockTransferRequest $request
     * @return Boolean
     */
    public function stockTransfer($request)
    {
        // Check shop id is owned for company admin role
        if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
            if ($request->shop_id != null) {
                $shopList = $this->shopDao->getShopTypeByCompanyID(Auth::guard('staff')->user()->company_id);
                $checkExists=$shopList->contains('id', $request->shop_id);
                if (! $checkExists) {
                    return $checkExists;
                }
            }

            $warehouseList = $this->warehouseDao->getWarehouseByCompanyID(Auth::guard('staff')->user()->company_id);
            $checkExists=$warehouseList->contains('id', $request->selected_warehouse_id);
            if (! $checkExists) {
                return $checkExists;
            }
            if ($request->warehouse_id != null) {
                $checkExists=$warehouseList->contains('id', $request->warehouse_id);
                if (! $checkExists) {
                    return $checkExists;
                }
            }
        }
        return $this->stockDao->stockTransfer($request);
    }

    /**
     * Get low stock info
     *
     * @return Object
     */
    public function lowStockAlert()
    {
        return $this->stockDao->lowStockAlert();
    }
}
