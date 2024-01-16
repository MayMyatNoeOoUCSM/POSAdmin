<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CategoryServiceInterface;
use App\Contracts\Services\DamageDetailServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Contracts\Services\SaleDetailServiceInterface;
use App\Contracts\Services\ShopServiceInterface;
use App\Http\Requests\ChangePriceRequest;
use App\Http\Requests\ProductExcelImportRequest;
use App\Http\Requests\ProductRequest;
use App\Imports\ProductImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    private $productService;
    private $categoryService;
    private $salesDetailService;
    private $damageDetailService;
    private $shopService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\ProductServiceInterface $ProductService
     * @param \App\Contracts\Services\SaleDetailServiceInterface $salesDetailService
     * @param \App\Contracts\Services\DamageDetailServiceInterface $damageDetailService
     * @param \App\Contracts\Services\CategoryServiceInterface $categoryService
     * @param \App\Contracts\Services\ShopServiceInterface $shopService
     * @return void
     */
    public function __construct(ProductServiceInterface $productService, CategoryServiceInterface $categoryService, SaleDetailServiceInterface $salesDetailService, DamageDetailServiceInterface $damageDetailService, ShopServiceInterface $shopService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->salesDetailService = $salesDetailService;
        $this->damageDetailService = $damageDetailService;
        $this->shopService = $shopService;
    }

    /**
     * Display a listing of the resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // product category list and product list
        $productCategoryList = $this->productService->getProductCategoryList();
        $productList = $this->productService->getProductList($request);
        return view('product.index', compact('productList', 'productCategoryList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // product category list
        $productCategoryList = $this->categoryService->getProductCategoryList();
        $shopProductList = $this->shopService->getShopDetailList();
        return view('product.create', compact('productCategoryList', 'shopProductList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        // check product category exists or not
        $category = $this->categoryService->getCategoryById($request->product_category_id);
        if (is_null($category)) {
            return back()->with('error_status', __('message.E0001'));
        }
        // store product info in storage and check return statement
        $result = $this->productService->insert($request, $category, 0);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Product']));
            return redirect()->route('product');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        // product category list and check return statement
        $productCategoryList = $this->categoryService->getProductCategoryList();
        $shopProductList = $this->shopService->getShopDetailList();
        if (is_null($productCategoryList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        $shop_id_array = [];
        foreach ($product->shop as $shop) {
            $shop_id_array[] = $shop->pivot->shop_id;
        }
        return view('product.edit', compact('product', 'productCategoryList', 'shopProductList', 'shop_id_array'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param \App\Models\Product $product
    * @return \Illuminate\Http\Response
    */
    public function clone(Product $product)
    {
        // product category list and check return statement
        $productCategoryList = $this->categoryService->getProductCategoryList();
        $shopProductList = $this->shopService->getShopDetailList();
        if (is_null($productCategoryList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        $shop_id_array = [];
        foreach ($product->shop as $shop) {
            $shop_id_array[] = $shop->pivot->shop_id;
        }
        return view('product.clone', compact('product', 'productCategoryList', 'shopProductList', 'shop_id_array'));
    }

    /**
     *
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
       
        // category object to use insert or update statment
        $category = $this->categoryService->getCategoryById($request->product_category_id);

        // update product info in storage and check return statment
        $result = $this->productService->update($request, $product, $category);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'Product']));
            return redirect()->route('product');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     *
     * Store clone new product resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function storeCloneProduct(ProductRequest $request)
    {
         
        // category object to use insert or update statment
        $category = $this->categoryService->getCategoryById($request->product_category_id);

        // insert product info in storage and check return statment
        $result = $this->productService->insert($request, $category, 0);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'Product']));
            return redirect()->route('product');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     *
     * Update minimum quantity in product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeMinQty(Request $request)
    {
        // update product min quantity in storage and check return statment
        $result = $this->productService->changeMinQty($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'Product']));
            return redirect()->route('product');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function delete(Product $product)
    {
        // sales and damage/loss count for product and check
        $salesDetailCount = $this->salesDetailService->getSalesDetailByProductId($product->id);
        $damageDetailCount = $this->damageDetailService->getDamageDetailByProductId($product->id);
        if ($salesDetailCount > 0 || $damageDetailCount > 0) {
            return back()->with('error_status', __('message.W0003'));
        }

        // remove product info in storage and check return statement
        $product = $this->productService->delete($product);
        if ($product) {
            return redirect()->route('product')->with('success_status', __('message.I0003', ["tbl_name" => 'Product']));
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Show the form for editing product price
     *
     * @return \Illuminate\Http\Response
     */
    /*
    public function changePrice()
    {
    $productList = $this->productService->getStockListByStock();
    if (is_null($productList)) {
    return back()->with('error_status', __('message.0001E'));
    }
    return view('product.change_price', compact('productList'));
    }
     */

    /**
     * Update product price in warehouse_shop_product table
     *
     * @param  \App\Http\Requests\ChangePriceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePrice(ChangePriceRequest $request)
    {
        // update product price in storage and check return statement
        $result = $this->productService->updatePrice($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'Warehouse Shop Product Relation table']));
            return redirect()->route('product');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Import news product excel and store in storage
     *
     * @param  \App\Http\Requests\ProductExcelImportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function importExcel(ProductExcelImportRequest $request)
    {
        try {
            // import excel and show success message
            Excel::import(new ProductImport($this->productService, $this->categoryService, $request), $request->importFile);
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Product']));
        } catch (\Exception $e) {
            // if error occurs, show error details message
            $request->session()->flash('error_status', $e->getMessage() . ' Please check your excel import file.!!!');
        }
        return redirect()->route('product');
    }
}
