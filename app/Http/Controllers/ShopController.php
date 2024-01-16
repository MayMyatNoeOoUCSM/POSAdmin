<?php

namespace App\Http\Controllers;

use App\Contracts\Services\ShopServiceInterface;
use App\Http\Requests\ShopRequest;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
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
    public function index(Request $request)
    {
        $shopList = $this->shopService->getShopList($request);
        if (is_null($shopList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('shop.index', compact('shopList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shop.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ShopRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShopRequest $request)
    {
        $result = $this->shopService->insert($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Shop']));
            return redirect()->route('shop');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function edit(Shop $shop)
    {
        return view('shop.edit', compact('shop'));
    }

    /**
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ShopRequest  $request
     * @param  \App\Models\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function update(ShopRequest $request, Shop $shop)
    {
        $result = $this->shopService->update($request, $shop);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'Shop']));
            return redirect()->route('shop');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function delete(Shop $shop)
    {
        $resultFlg = $this->shopService->delete($shop);
        //Stock exist in warehouse and currently can't not delete
        if ($resultFlg == 1) {
            return back()->with('error_status', __('message.W0004'));
        }
        if ($resultFlg == 2) {
            return redirect()->route('shop')->with('success_status', __('message.I0003', ["tbl_name" => 'Shop']));
        }
        //DB exception occured
        return back()->with('error_status', __('message.E0001'));
    }
}
