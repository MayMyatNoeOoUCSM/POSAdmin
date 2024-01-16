<?php

namespace App\Contracts\Dao;

/**
 * ProductDaoInterface
 */
interface ProductDaoInterface
{

    /**
     * Get Product Category List from storage
     *
     * @return Object
     */
    public function getProductCategoryList();

    /**
     * Get Product Category List By Category from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getProductListByCategory($request);

    /**
     * Get Product List by Category Warehouse Shop from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getProductListByCategoryWarehouseShop($request);

    /**
     * Get Product List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getProductList($request);

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getProductDataExport($request);

    /**
     * Get Product List from storage
     *
     * @return Object
     */
    public function getAllProductList();

    /**
     * Get Product List from storage
     *
     * @param Integer $shopId
     * @return Object
     */
    public function getProductListwithPriceByShopId($shopId);

    /**
     * Get Stock List by Stock from storage
     *
     * @return Object
     */
    public function getStockListByStock();

    /**
     * Get Product Count By category Id from storage
     *
     * @param Integer $categoryId
     * @return Integer
     */
    public function getProductCountByCategoryId($categoryId);

    /**
     * Get last product from storage
     *
     * @param \app\Models\Category $category
     * @return Object
     */
    public function getLastProduct($category);

    /**
     * Product data is saved into storage
     *
     * @param \Illuminate\Http\Request $request
     * @param String $barcode
     * @param Integer $product_id
     * @return Object $product
     */
    public function insert($request, $barcode, $product_id);

    /**
     * Staff data is update into storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \app\Models\Product $product
     * @return Object $product
     */
    public function update($request, $product);

    /**
     * Change Min Qty for product
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function changeMinQty($request);

    /**
     * Delete Product By Id from storage
     *
     * @param \app\Models\Product $product
     * @return Object $product
     */
    public function delete($product);

    /**
     * Update Product Price into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function updatePrice($request);

    /**
     * Get Last 5 row Product List from storage
     *
     * @return Object
     */
    public function getLastFiveProductList();

    /**
     * Get New Product of Today
     *
     * @return Object
     */
    public function getNewProductByToday();

    /**
     * Insert Multi Product By Excel Import
     *
     * @param Object $data
     * @param \app\Models\Category $category
     * @return Object
     */
    public function insertMultiProduct($data, $category);

    /**
     * Get product details info search by product id
     *
     * @param  Integer $product_id
     * @return Object
     */
    public function details($product_id);

    /**
     * Get product list by search name
     *
     * @param \Illuminate\Http\ProductSearchRequest $request
     * @return Object $productList
     */
    public function getSearchProduct($request);

    /**
     * Get product list by category id
     *
     * @param \Illuminate\Http\ProductListByCategoryIDRequest $request
     * @return Object $productList
     */
    public function getProductListByCategoryID($request);

    /**
     * Get product info by product code
     *
     * @param \Illuminate\Http\ProductSearchByProductCodeRequest $request
     * @return Object $product
     */
    public function getProductByProductCode($request);

    /**
     * Check product exists check by product_id array
     *
     * @param  Array $product_array
     * @return Boolean
     */
    public function checkProductArrayExists($product_array);

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getInventoryStockProductDataExport($request);
}
