<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\ShopServiceInterface;
use App\Http\Controllers\Api\ApiController;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends ApiController
{
    private $shopService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\ShopServiceInterface $shopService
     * @return void
     */
    public function __construct(ShopServiceInterface $shopservice)
    {
        $this->shopService = $shopservice;
    }

    /**
     *
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {
    //     $shopList = Shop::get();
    //     return $this->showAll($shopList);
    // }
}
