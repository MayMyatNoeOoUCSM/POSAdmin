<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CategoryServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Contracts\Services\ShopServiceInterface;
use App\Contracts\Services\StockServiceInterface;
use App\Contracts\Services\WarehouseServiceInterface;
use App\Http\Requests\StockRequest;
use App\Http\Requests\StockTransferRequest;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    private $warehouseService;
    private $shopService;
    private $productService;
    private $categoryService;
    private $stockService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\WarehouseServiceInterface $warehouseService
     * @param \App\Contracts\Services\StockServiceInterface $stockService
     * @param \App\Contracts\Services\ShopServiceInterface $shopService
     * @param \App\Contracts\Services\ProductServiceInterface $warehouseService
     * @param \App\Contracts\Services\CategoryServiceInterface $categoryService
     * @return void
     */
    public function __construct(StockServiceInterface $stockService, WarehouseServiceInterface $warehouseService, ShopServiceInterface $shopService, ProductServiceInterface $productService, CategoryServiceInterface $categoryService)
    {
        $this->warehouseService = $warehouseService;
        $this->shopService = $shopService;
        $this->productService = $productService;
        $this->stockService = $stockService;
        $this->categoryService = $categoryService;
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
        // stock , warehouse and shop list
        $stockList = $this->stockService->getCurrentStockList($request);
        $warehouseList = $this->warehouseService->getAllWarehouseList();
        $shopList = $this->shopService->getAllShopList();
        return view('stock.index', compact('stockList', 'warehouseList', 'shopList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // product and product category list
        $productList = $this->productService->getAllProductList();
        $productCategoryList = $this->categoryService->getProductCategoryList();
        return view('stock.create', compact('productList', 'productCategoryList'));
    }

    /**
     * Get subcategory list search by parentcategory
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_sub_category(Request $request)
    {
        // product sub category and product list
        $productSubCategoryList = $this->categoryService->getProductSubCategoryListByParentCategoryId($request->productCategory);
        $productList = $this->productService->getProductListByCategory($request);
        if (is_null($productSubCategoryList)) {
            return response()->json(__('message.E0001'), 422);
        }
        $response = [
            'productSubCategoryList' => $productSubCategoryList,
            'productList' => $productList
        ];
        return response()->json($response, 200);
    }

    /**
     * Get product list search by category
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_product(Request $request)
    {
        // product list
        $productList = $this->productService->getProductListByCategory($request);
        if (is_null($productList)) {
            return response()->json(__('message.E0001'), 422);
        }
        $response = ['productList' => $productList];
        return response()->json($response, 200);
    }

    /**
     * Search Product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search_product(Request $request)
    {
        // warehouse and shop list
        $warhouseList = $this->warehouseService->getAllWarehouseList();
        $shopList = $this->shopService->getAllShopList();

        // request product search from damage&loss and stock
        if ($request->damage_loss_search == 1) {
            $productList = $this->productService->getProductListByCategoryWarehouseShop($request);
        } else {
            $productList = $this->productService->getProductListByCategory($request);
        }
        $response = [
            'warhouseList' => $warhouseList,
            'shopList' => $shopList,
            'productList' => $productList
        ];
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StockRequest $request)
    {
        // store stock info in storage and check return statement
        $result = $this->stockService->insert($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Stock']));
            return redirect()->route('stock');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Transfer stock form from warehouse/shop to warehouse
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transfer(Request $request)
    {
        // transfer product info from warehouse shop product
        $warehouseShopProduct =  $this->stockService->getwarehouseShopProduct($request);

        // warehouse list that have stock
        $warehouseStockList =  $this->stockService->getWarehouseStockList($request);
        if (sizeof($warehouseStockList) == 0) {
            return back()->with('error_status', __('message.W0006', ["product_name" => $warehouseShopProduct->product_name]));
        }
        return view('stock.transfer', compact('warehouseShopProduct', 'warehouseStockList'));
    }

    /**
     * Store stock from other warehouse/shop by transfering
     *
     * @param App\Http\Requests\StockTransferRequest $request
     * @return \Illuminate\Http\Response
     */
    public function transferStore(StockTransferRequest $request)
    {
        // check transfer stock quantity
        if ($request->qty > $request->old_qty) {
            return back()->with('error_status', __('message.W0005'));
        }
        // update stock transfer info and check return statement
        $result = $this->stockService->stockTransfer($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Stock']));
            return redirect()->route('stock');
        }
        return back()->with('error_status', __('message.E0001'));
    }
}
