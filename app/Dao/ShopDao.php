<?php

namespace App\Dao;

use App\Contracts\Dao\ShopDaoInterface;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

/**
 * Shop Dao
 *
 * @author
 */
class ShopDao implements ShopDaoInterface
{

    /**
     * Get Shop List from table
     *
     * @return Object $shopList
     */
    public function getShopList($request)
    {
        $shopList = Shop::where('is_deleted', config('constants.DEL_FLG_OFF'))
            ->paginate($request->custom_pg_size == "" ? config('constants.SHOP_PAGINATION') : $request->custom_pg_size);
        return $shopList;
    }

    /**
     * Get Shop List from table
     *
     * @return Object $shopList
     */
    public function getAllShopList()
    {
        $shopList = Shop::where('is_deleted', config('constants.DEL_FLG_OFF'))->get();
        return $shopList;
    }

    /**
     * get shop list from storage
     *
     * @return Object $shopCategoryList
     */
    public function getShopDetailList()
    {
        $shopCategoryList = Shop::where('is_deleted', config('constants.DEL_FLG_OFF'))->get();
        return $shopCategoryList;
    }

    /**
     * shop data is saved into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $shop
     */
    public function insert($request)
    {
        $shop = new Shop;
        $shop->company_id = Auth::user()->company_id;
        $shop->name = $request->name;
        $shop->shop_type = $request->shop_type;
        $shop->address = $request->address;
        $shop->phone_number_1 = $request->phone_number_1;
        $shop->phone_number_2 = $request->phone_number_2;
        $shop->is_deleted = config('constants.DEL_FLG_OFF');
        $shop->create_user_id = auth()->user()->id;
        $shop->update_user_id = auth()->user()->id;
        $shop->create_datetime = Date('Y-m-d H:i:s');
        $shop->update_datetime = Date('Y-m-d H:i:s');
        $shop->save();
        return $shop;
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
        $shop->name = $request->name;
        $shop->shop_type = $request->shop_type;
        $shop->address = $request->address;
        $shop->phone_number_1 = $request->phone_number_1;
        $shop->phone_number_2 = $request->phone_number_2;
        $shop->update_user_id = auth()->user()->id;
        $shop->update_datetime = Date('Y-m-d H:i:s');
        $shop->save();
        return $shop;
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
        $shop = Shop::where('id', $shop->id)->update(['is_deleted' => config('constants.DEL_FLG_ON')]);
        return $shop;
    }

    /**
     * Get shop details info search by shop id
     *
     * @param  Integer $shop_id
     * @return Object
     */
    public function details($shop_id)
    {
        return Shop::find(1);
    }

    /**
     * Get shop type info by company id
     *
     * @param  Integer $company_id
     * @return Object $shopList
     */
    public function getShopTypeByCompanyID($company_id)
    {
        $shopList = Shop::where('is_deleted', config('constants.DEL_FLG_OFF'))
            ->where('company_id', $company_id)
            ->get();
        return $shopList;
    }
}
