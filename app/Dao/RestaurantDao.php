<?php

namespace App\Dao;

use App\Contracts\Dao\RestaurantDaoInterface;
use App\Models\Restaurant;

/**
 * Terminal Dao
 *
 * @author
 */
class RestaurantDao implements RestaurantDaoInterface
{
    /**
     * Get restaurant list from storage
     *
     * @return Object $restaurantList
     */
    public function getRestaurantList($request)
    {
        $restaurantList = Restaurant::leftJoin('m_shop as s', 's.id', '=', 'm_restaurant_table.shop_id')
            ->where('m_restaurant_table.is_deleted', config('constants.DEL_FLG_OFF'))
            ->paginate(config('constants.RESTAURANT_TABLE_PAGINATION'), ['m_restaurant_table.*', 's.name as shop_name']);
        return $restaurantList;
    }

    /**
     * Store restaurant info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return $restaurant
     */
    public function insert($request)
    {
        $restaurant = new Restaurant;
        $restaurant->name = $request->name;
        $restaurant->shop_id = $request->shop_id;
        $restaurant->total_seats_people = $request->total_seat_people;
        $restaurant->available_status = $request->active ? config('constants.ACTIVE') : config('constants.IN_ACTIVE');
        $restaurant->is_deleted = config('constants.DEL_FLG_OFF');
        $restaurant->create_user_id = auth()->user()->id;
        $restaurant->update_user_id = auth()->user()->id;
        $restaurant->create_datetime = Date('Y-m-d H:i:s');
        $restaurant->update_datetime = Date('Y-m-d H:i:s');
        $restaurant->save();
        return $restaurant;
    }

    /**
     * update restaurant info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $restaurant
     */
    public function update($request)
    {
        $restaurant = Restaurant::find($request->id);
        $restaurant->name = $request->name;
        //$restaurant->shop_id = $request->shop_id;
        $restaurant->total_seats_people = $request->total_seat_people;
        $restaurant->available_status = $request->active ? config('constants.ACTIVE') : config('constants.IN_ACTIVE');
        $restaurant->update_user_id = auth()->user()->id;
        $restaurant->update_datetime = Date('Y-m-d H:i:s');
        $restaurant->save();
        return $restaurant;
    }

    /**
     * Remove specified restaurant from storage
     *
     * @param \App\Models\Restaurant $restaurant
     * @return Object $restaurant
     */
    public function delete($restaurant)
    {
        $restaurant = Restaurant::where('id', $restaurant->id)->update(['is_deleted' => config('constants.DEL_FLG_ON')]);
        return $restaurant;
    }

    /**
     * Get restaurant table list from storage
     *
     * * @param \Illuminate\Http\Request $request
     * @return Object $restaurantTableList
     */
    public function getRestaurantTableListAtKitchen($request)
    {
        $restaurantTableList = Restaurant::leftJoin('m_shop as s', 's.id', '=', 'm_restaurant_table.shop_id')
            ->join('t_order', 't_order.restaurant_table_id', '=', 'm_restaurant_table.id')
            ->join('t_order_details', 't_order_details.order_id', '=', 't_order.id')

            ->where('m_restaurant_table.available_status', config('constants.RESTAURANT_TABLE_ORDER'))
            ->where('t_order.order_status', config('constants.ORDER_CREATE'))
            ->where('t_order_details.order_details_status', config('constants.ORDER_DETAILS_CREATE'))
            ->groupBy('m_restaurant_table.id')
        // ->whereHas('orderdetails', function ($query) {
        //     return $query->where('t_order_details.order_details_status', '=', config('constans.ORDER_DETAILS_CREATE'));
        // })
            ->paginate(config('constants.RESTAURANT_TABLE_PAGINATION'), ['m_restaurant_table.*']);
        return $restaurantTableList;
    }

    /**
     * Check table available free or order
     *
     * @param  Integer $restaurant_table_id
     * @return Boolean
     */
    public function checkTableAvailable($restaurant_table_id)
    {
        $status = Restaurant::where('m_restaurant_table.available_status', config('constants.RESTAURANT_TABLE_FREE'))
            ->where('m_restaurant_table.id', $restaurant_table_id)
            ->first();
        return $status;
    }
}
