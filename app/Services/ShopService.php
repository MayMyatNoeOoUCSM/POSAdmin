<?php

namespace App\Services;

use App\Contracts\Dao\ShopDaoInterface;
use App\Contracts\Services\ShopServiceInterface;

class ShopService implements ShopServiceInterface
{
    private $shopDao;

    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\ShopDaoInterface $shopDao
     * @return void
     */
    public function __construct(ShopDaoInterface $shopDao)
    {
        $this->shopDao = $shopDao;
    }

    /**
     * Get shop list
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $shopList
     */
    public function getShopList($request)
    {
        return $this->shopDao->getShopList($request);
    }

    /**
     * Get all shop list
     *
     * @return Object $shopList
     */
    public function getAllShopList()
    {
        return $this->shopDao->getAllShopList();
    }

    /**
     * Store shop info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $shop
     */
    public function insert($request)
    {
        return $this->shopDao->insert($request);
    }

    /**
     * Update shop data in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Shop $shop
     * @return Object $shop
     */
    public function update($request, $shop)
    {
        return $this->shopDao->update($request, $shop);
    }

    /**
     * Remove shop info in storage
     *
     * @param \App\Models\Shop $shop
     * @return Object $shop
     *
     */
    public function delete($shop)
    {
        return $this->shopDao->delete($shop);
    }

    /**
     * get shop list from storage
     * @return Object $shopDetailList
     */
    public function getShopDetailList()
    {
        return $this->shopDao->getShopDetailList();
    }

    /**
     * Get shop type info by company id
     *
     * @param  Integer $company_id
     * @return String restaurant or retails or both
     */
    public function getShopTypeByCompanyID($company_id)
    {
        $shopTypeConstants = [config('constants.RETAILS_SHOP'), config('constants.RESTAURANT_SHOP')];
        $shopList = $this->shopDao->getShopTypeByCompanyID($company_id);
        $shopType = [];
        foreach ($shopList as $key => $value) {
            $shopType[] = $value->shop_type;
        }
        if (in_array(config('constants.RETAILS_SHOP'), $shopType) && in_array(config('constants.RESTAURANT_SHOP'), $shopType)) {
            return "both";
        }
        if (in_array(config('constants.RETAILS_SHOP'), $shopType)) {
            return "retails";
        }
        return "restaurant";
    }
}
