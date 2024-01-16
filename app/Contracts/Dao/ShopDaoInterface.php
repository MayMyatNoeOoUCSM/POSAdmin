<?php

namespace App\Contracts\Dao;

/**
 * ShopDaoInterface
 */
interface ShopDaoInterface
{

    /**
     * Get Shop List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object shopList
     */
    public function getShopList($request);

    /**
     * Get Shop List from storage
     *
     * @return Object shopList
     */
    public function getAllShopList();

    /**
     * get shop list from storage
     *
     * @return Object $shopCategoryList or null
     */
    public function getShopDetailList();

    /**
     * Shop data is saved into storage
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Object $shop
     */
    public function insert($request);

    /**
     * Update Shop data into storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \app\Models\Warehouse $warehouse
     * @return Object $shop
     */
    public function update($request, $warehouse);

    /**
     * Delete Shop By Id from storage
     *
     * @param \app\Models\Shop $shop
     *
     * @return Object $shop
     */
    public function delete($shop);

    /**
     * Get shop details info search by shop id
     *
     * @param  Integer $shop_id
     * @return Object
     */
    public function details($shop_id);

    /**
     * Get shop type info by company id
     *
     * @param  Integer $company_id
     * @return Object $shopList
     */
    public function getShopTypeByCompanyID($company_id);
}
