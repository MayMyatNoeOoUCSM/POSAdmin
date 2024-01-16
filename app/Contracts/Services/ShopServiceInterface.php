<?php

namespace App\Contracts\Services;

interface ShopServiceInterface
{

    /**
     * Get shop list
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $shopList
     */
    public function getShopList($request);

    /**
     * Get all shop list
     *
     * @return Object shopList
     */
    public function getAllShopList();

    /**
     * Store shop info in storage
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Object $shop
     */
    public function insert($request);

    /**
     * get shop list from storage
     *
     * @return Object $shopDetailList
     */
    public function getShopDetailList();

    /**
     * Update shop data in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Shop $shop
     * @return Object $shop
     */
    public function update($request, $shop);

    /**
     * Remove shop info in storage
     *
     * @param \App\Models\Shop $shop
     * @return Object $shop
     *
     */
    public function delete($shop);

    /**
     * Get shop type info by company id
     *
     * @param  Integer $company_id
     * @return Object $shopList
     */
    public function getShopTypeByCompanyID($company_id);
}
