<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CategoryServiceInterface;
use App\Contracts\Services\DamageDetailServiceInterface;
use App\Contracts\Services\DamageLossServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Contracts\Services\ShopServiceInterface;
use App\Contracts\Services\WarehouseServiceInterface;
use App\Http\Requests\DamageLossRequest;
use Illuminate\Http\Request;

class DamageLossController extends Controller
{
    private $damageLossService;
    private $damageDetailService;
    private $productService;
    private $categoryService;
    private $warehouseService;
    private $shopService;


    /**
    * Create a new controller instance.
    *
    * @param DamageLossServiceInterface $damageLossService
    * @param DamageDetailServiceInterface $damageDetailService
    * @param ProductServiceInterface $productService
    * @param CategoryServiceInterface $categoryService
    * @param WarehouseServiceInterface $warehouseService
    * @param ShopServiceInterface $shopService
    *
    *
    * @return void
    */
    public function __construct(DamageLossServiceInterface $damageLossService, DamageDetailServiceInterface $damageDetailService, ProductServiceInterface $productService, CategoryServiceInterface $categoryService, WarehouseServiceInterface $warehouseService, ShopServiceInterface $shopService)
    {
        $this->damageLossService    = $damageLossService;
        $this->damageDetailService  = $damageDetailService;
        $this->productService       = $productService;
        $this->categoryService      = $categoryService;
        $this->warehouseService     = $warehouseService;
        $this->shopService          = $shopService;
    }

    /**
     * Display a listing of the resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // damage&loss list and shop list
        $damageLossList = $this->damageLossService->getDamageLossList($request);
        $shopList = $this->shopService->getAllShopList();
        if (is_null($damageLossList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('damageloss.index', compact('damageLossList', 'shopList'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        // product, productcategory, warehouse and shop list
        $productList = $this->productService->getAllProductList();
        $productCategoryList = $this->categoryService->getProductCategoryList();
        $warehouseList = $this->warehouseService->getAllWarehouseList();
        $shopList = $this->shopService->getAllShopList();
        return view('damageloss.create', compact('productList', 'productCategoryList', 'warehouseList', 'shopList'));
    }

    /**
    * Store a newly created resource in storage
    *
    * @param  \App\Http\Requests\DamageLossRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function store(DamageLossRequest $request)
    {
        // store damage&loss info in main table and check return statement
        $damage_loss_id = $this->damageLossService->insert($request, config('constants.FRM_DAMAGE_LOSS'));
        if (is_numeric($damage_loss_id)) {
            // store damage&loss info in details table and check return statement
            $result = $this->damageDetailService->insert($request, $damage_loss_id);
            if ($result) {
                $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Damage Loss']));
                return redirect()->route('damageloss');
            }
        } else {
            //$request->session()->flash('error_status', $damage_loss_id);
            $request->session()->flash('error_status', __('message.E0001'));

            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request)
    {
        // damage&loss details info and check retrun statement
        $result       = $this->damageDetailService->details($request->shop_id, $request->warehouse_id, $request);
        if ($result) {
            return view('damageloss.checkdetails', compact('result'));
        }
        return back()->with('error_status', __('message.E0001'));
    }
}
