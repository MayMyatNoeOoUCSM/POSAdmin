<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\RestaurantServiceInterface;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\RestaurantResource;
use App\Http\Resources\RestaurantTableListAtCashierResource;
use App\Http\Resources\RestaurantTableListAtKitchenResource;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantTableController extends ApiController
{
    private $restaurantService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\RestaurantServiceInterface $restaurantService
     * @return void
     */
    public function __construct(RestaurantServiceInterface $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    /**
     * Restaurant Table List
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function index(Request $request)
    {
        $orderList = $this->restaurantService->getRestaurantList($request);
        
        return RestaurantResource::collection($orderList)->additional(
            ['status'=>'success',
                'message'=>'Successfully retrieved restaurant table list']
        );
    }

    /**
     * Restaurant Table List At Kitchen
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function restaurantTableListAtKitchen(Request $request)
    {
        $tableList = $this->restaurantService->getRestaurantTableListAtKitchen($request);
        return RestaurantTableListAtKitchenResource::collection($tableList)
            ->additional(['status'=>'success',
                'message'=>'Successfully retrieved restaurant table list.']);
    }

    /**
     * Restaurant Table List At Cashier
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function restaurantTableListAtCashier(Request $request)
    {
        $tableList = $this->restaurantService->getRestaurantList($request);
        return RestaurantTableListAtCashierResource::collection($tableList)
            ->additional(['status'=>'success',
                'message'=>'Successfully retrieved restaurant table list.']);
    }
}
