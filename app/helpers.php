<?php
/**
 * Check Shop Type
 * @return String both,retails and restaurant for company admin and String retails and restaurant for shop admin
 */
function checkShopOwnerType()
{
    if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
        $shopList = \App\Models\Shop::where('is_deleted', config('constants.DEL_FLG_OFF'))
            ->where('company_id', Auth::guard('staff')->user()->company_id)
            ->get();
        $shopTypeConstants = [config('constants.RETAILS_SHOP'), config('constants.RESTAURANT_SHOP')];
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
    if (Auth::guard('staff')->user()->role == config('constants.SHOP_ADMIN')) {
        $shop = \App\Models\Shop::where('is_deleted', config('constants.DEL_FLG_OFF'))
            ->where('id', Auth::guard('staff')->user()->shop_id)
            ->first();
        if ($shop->shop_type == config('constants.RETAILS_SHOP')) {
            return "retails";
        }
        return "restaurant";
    }
}
/**
 * Check Company License Status
 * @return Integer 1 (active) 2 (inactive or expired or block)
 */
function checkCompanyLicenseIsActive()
{
    if (Auth::guard('staff')->user()->role == config('constants.ADMIN')) {
        return 1;
    }
    if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
        $license = \App\Models\CompanyLicense
        ::where("company_id", Auth::guard('staff')->user()->company_id)
            ->where("status", config("constants.COMPANY_LICENSE_ACTIVE"))
            ->get();
        if ($license->count() > 0) {
            return 1;
        }
        return 2;
    }
    if (Auth::guard('staff')->user()->role == config('constants.SHOP_ADMIN') or
        Auth::guard('staff')->user()->role == config('constants.CASHIER_STAFF') or
        Auth::guard('staff')->user()->role == config('constants.KITCHEN_STAFF') or
        Auth::guard('staff')->user()->role == config('constants.WAITER_STAFF') or
        Auth::guard('staff')->user()->role == config('constants.SALE_STAFF')
        ) {
        $company_id = \App\Models\Shop::where("id", Auth::guard('staff')->user()->shop_id)->first()->company_id;
        $license = \App\Models\CompanyLicense
        ::where("company_id", $company_id)
            ->where("status", config("constants.COMPANY_LICENSE_ACTIVE"))
            ->get();
        if ($license->count() > 0) {
            return 1;
        }
        return 2;
    }
}
