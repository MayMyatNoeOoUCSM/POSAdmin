<?php

namespace App\Services;

use App\Contracts\Dao\DamageLossDaoInterface;
use App\Contracts\Dao\ProductDaoInterface;
use App\Contracts\Dao\ShopDaoInterface;
use App\Contracts\Dao\StaffDaoInterface;
use App\Contracts\Dao\WarehouseDaoInterface;
use App\Contracts\Services\DamageLossServiceInterface;
use Illuminate\Support\Facades\Auth;

class DamageLossService implements DamageLossServiceInterface
{
    private $damageDao;
    private $productDao;
    private $warehouseDao;
    private $shopDao;
    private $staffDao;

    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\DamageLossDaoInterface $damageDao
     * @param \App\Contracts\Dao\ProductDaoInterface $productDao
     * @param \App\Contracts\Dao\WarehouseDaoInterface $warehouseDao
     * @param \App\Contracts\Dao\ShopDaoInterface $shopDao
     * @param \App\Contracts\Dao\StaffDaoInterface $staffDao
     * @return void
     */
    public function __construct(DamageLossDaoInterface $damageDao, ProductDaoInterface $productDao, WarehouseDaoInterface $warehouseDao, ShopDaoInterface $shopDao, StaffDaoInterface $staffDao)
    {
        $this->damageDao = $damageDao;
        $this->productDao = $productDao;
        $this->warehouseDao = $warehouseDao;
        $this->shopDao = $shopDao;
        $this->staffDao = $staffDao;
    }

    /**
     * Get damage&loss list search by shop or warehouse
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getDamageLossList($request)
    {
        return $this->damageDao->getDamageLossList($request);
    }

    /**
     * Store Damage&Loss info in storage
     *
     * @param \App\Http\Requests\DamageLossRequest $request
     * @param Integer $sourceFrom
     * @return Integer $damage&loss id
     */
    public function insert($request, $sourceFrom)
    {
        // Check shop id is owned for company admin role
        if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
            if (isset($request->shop_id) && $request->shop_id != null) {
                $shopList = $this->shopDao->getShopTypeByCompanyID(Auth::guard('staff')->user()->company_id);
                $checkExists=$shopList->contains('id', $request->shop_id);
                if (! $checkExists) {
                    return $checkExists;
                }
            }

            if (isset($request->warehouse_id) && $request->warehouse_id != null) {
                $warehouseList = $this->warehouseDao->getWarehouseByCompanyID(Auth::guard('staff')->user()->company_id);
                $checkExists=$warehouseList->contains('id', $request->warehouse_id);
                if (! $checkExists) {
                    return $checkExists;
                }
            }
        }
        // Check shop id is owned for shop admin role
        if (Auth::guard('staff')->user()->role == config('constants.SHOP_ADMIN')) {
            if ($request->shop_id != Auth::guard('staff')->user()->shop_id) {
                return false;
            }
        }

        if ($sourceFrom == config('constants.FRM_DAMAGE_LOSS')) {
            for ($i = 0; $i < count($request->qty); $i++) {
                $productStockQty = $this->damageDao->getProductStockQty($request, $request->product_id[$i], $i);

                if (! $productStockQty) {
                    $product = $this->productDao->details($request->product_id[$i]);
                    if ($request->warehouse_id) {
                        $warehouse = $this->warehouseDao->details($request->warehouse_id);
                        $place = " Warehouse (" . $warehouse->name . ")";
                    } else {
                        $shop = $this->shopDao->details($request->shop_id);
                        $place = " Shop (" . $shop->name . ")";
                    }
                    return "Product ($product->name) " . $request->qty[$i] . " quantity is not enough stock at" . $place . ". Please check again.";
                }
            }
        }
  
        $result = $this->damageDao->insert($request);
        // Insert new damage & loss notification
        // if (is_numeric($result)) {
        //     $staffList = $this->staffDao->getStaffListByShopIDArray([$request->shop_id]);
        //     $messageInfo = [
        //         'body' => 'New Damage & Loss Process!',
        //         'type' => config('constants.NOTIFICATION_DAMAGE_LOSS'),
        //     ];
        //     foreach ($staffList as $key => $value) {
        //         $staff = \App\Models\Staff::find($value->id);
        //         $staff->notify(new \App\Notifications\AdminNotification($messageInfo));
        //     }
        // }
        return $result;
    }

    /**
     * Get damage&loss total count for today
     *
     * @return Integer
     */
    public function getDamageLossByToday()
    {
        return $this->damageDao->getDamageLossByToday();
    }

    /**
     * Get damageloss list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $damagelossList
     */
    public function getDamageLossDataExport($request)
    {
        return $this->damageDao->getDamageLossDataExport($request);
    }
}
