<?php

namespace App\Contracts\Dao;

/**
 * RestaurantDaoInterface
 */
interface RestaurantDaoInterface
{

    /**
     * Get Restaurant List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object restaurantList
     */
    public function getRestaurantList($request);

    /**
     * store restaurant info into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $restaurant
     */
    public function insert($request);

    /**
     * update restaurant info in storage
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Object $restaurant
     */
    public function update($request);

    /**
     * remove restaurant from storage
     *
     * @param \App\Models\Restaurant $restaurant
     *
     * @return Object $restaurant
     */
    public function delete($restaurant);

    /**
     * Get restaurant table list from storage
     *
     * * @param \Illuminate\Http\Request $request
     * @return Object $restaurantTableList
     */
    public function getRestaurantTableListAtKitchen($request);

    /**
     * Check table available free or order
     *
     * @param  Integer $restaurant_table_id
     * @return Boolean
     */
    public function checkTableAvailable($restaurant_table_id);
}
