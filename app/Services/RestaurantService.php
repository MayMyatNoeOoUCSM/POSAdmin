<?php

namespace App\Services;

use App\Contracts\Dao\RestaurantDaoInterface;
use App\Contracts\Dao\ShopDaoInterface;
use App\Contracts\Services\RestaurantServiceInterface;
use Auth;

class RestaurantService implements RestaurantServiceInterface
{
    private $shopDao;
    private $restaurantDao;

    /**
     * Class Constructor
     *
     * @param App\Contracts\Dao\ShopDaoInterface $shopDao
     * @param App\Contracts\Dao\RestaurantDaoInterface $restaurantDao
     * @return void
     */
    public function __construct(ShopDaoInterface $shopDao, RestaurantDaoInterface $restaurantDao)
    {
        $this->shopDao = $shopDao;
        $this->restaurantDao = $restaurantDao;
    }

    /**
     * Get restaurant list from storage
     *
     * @return Object $restaurantList
     */
    public function getRestaurantList($request)
    {
        return $this->restaurantDao->getRestaurantList($request);
    }

    /**
     * store restaurant info into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $restaurant
     */
    public function insert($request)
    {
        // Check shop id is owned for company admin role
        if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
            $shopList = $this->shopDao->getShopTypeByCompanyID(Auth::guard('staff')->user()->company_id);
            $checkExists=$shopList->contains('id', $request->shop_id);
            if (! $checkExists) {
                return $checkExists;
            }
        }
        // Check shop id is owned for shop admin role
        if (Auth::guard('staff')->user()->role == config('constants.SHOP_ADMIN')) {
            if (Auth::guard('staff')->user()->shop_id !== $request->shop_id) {
                return false;
            }
        }
        return $this->restaurantDao->insert($request);
    }

    /**
     * update restaurant info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $restaurant
     */
    public function update($request)
    {
        return $this->restaurantDao->update($request);
    }

    /**
     * remove restaurant from storage
     *
     * @param \App\Models\Restaurant $restaurant
     * @return Object $restaurant
     */
    public function delete($restaurant)
    {
        return $this->restaurantDao->delete($restaurant);
    }

    /**
     * Get restaurant table list from storage
     *
     * * @param \Illuminate\Http\Request $request
     * @return Object $restaurantTableList
     */
    public function getRestaurantTableListAtKitchen($request)
    {
        return $this->restaurantDao->getRestaurantTableListAtKitchen($request);
    }

    /**
     * Check table available free or order
     *
     * @param  Integer $restaurant_table_id
     * @return Boolean
     */
    public function checkTableAvailable($restaurant_table_id)
    {
        return $this->restaurantDao->checkTableAvailable($restaurant_table_id);
    }
}
