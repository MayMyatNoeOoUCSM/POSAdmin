<?php

namespace App\Http\Controllers;

use App\Contracts\Services\RestaurantServiceInterface;
use App\Contracts\Services\ShopServiceInterface;
use App\Http\Requests\RestaurantRequest;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    private $restaurantService;
    private $shopService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\RestaurantServiceInterface $restaurantService
     * @param \App\Contracts\Services\ShopServiceInterface $shopService
     * @return void
     */
    public function __construct(RestaurantServiceInterface $restaurantService, ShopServiceInterface $shopService)
    {
        $this->restaurantService = $restaurantService;
        $this->shopService = $shopService;
    }

    /**
     *
     * Display a listing of the resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //restaurant list
        $restaurantList = $this->restaurantService->getRestaurantList($request);
        if (is_null($restaurantList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('restaurant.index', compact('restaurantList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //shop list object to create new terminal
        $shopList = $this->shopService->getAllShopList();
        return view('restaurant.create', compact('shopList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RestaurantRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RestaurantRequest $request)
    {
        //store new restaurant info in storage and check return statement
        $result = $this->restaurantService->insert($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Restaurant']));
            return redirect()->route('restaurant');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Restaurant $restaurant
     * @return \Illuminate\Http\Response
     */
    public function edit(Restaurant $restaurant)
    {
        //shop list object to update terminal info in storage
        $shopList = $this->shopService->getAllShopList();
        return view('restaurant.edit', compact('restaurant', 'shopList'));
    }

    /**
     *
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RestaurantRequest  $request
     * @param  \App\Models\Restaurant $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(RestaurantRequest $request)
    {
        //update terminal info in storage and check return statement
        $result = $this->restaurantService->update($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'Restaurant']));
            return redirect()->route('restaurant');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restaurant $restaurant
     * @return \Illuminate\Http\Response
     */
    public function delete(Restaurant $restaurant)
    {
        //remove terminal from storage and check return statement
        $restaurant = $this->restaurantService->delete($restaurant);
        if ($restaurant) {
            return redirect()->route('restaurant')->with('success_status', __('message.I0003', ["tbl_name" => 'Restaurant']));
        }
        return back()->with('error_status', __('message.E0001'));
    }
}
