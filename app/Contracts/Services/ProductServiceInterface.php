<?php

namespace App\Contracts\Services;

interface ProductServiceInterface
{
    /**
     * Get product list and category name
     *
     * @return Object
     */
    public function getProductCategoryList();

    /**
     * Get product list search by product id, product sub category or product category
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $productList
     */
    public function getProductListByCategory($request);

    /**
     * Get product list and product price
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $productList
     */
    public function getProductListByCategoryWarehouseShop($request);

    /**
     * Get product list
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $productList
     */
    public function getProductList($request);

    /**
     * Get all product list
     *
     * @return Object $productList
     */
    public function getAllProductList();

    /**
     * Get product list that have stock
     *
     * @return Object $productList
     */
    public function getStockListByStock();

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getProductDataExport($request);

    /**
     * Get total product count search by category
     *
     * @param Integer $categoryId
     * @return Integer
     */
    public function getProductCountByCategoryId($categoryId);

    /**
     * Store product info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param Object $category
     * @param Integer $product_id
     * @return Object $product
     */
    public function insert($request, $category, $product_id);

    /**
     * Update product info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @param Object $category
     * @return Object $product
     */
    public function update($request, $product, $category);

    /**
     * Update minimun quantity for product in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function changeMinQty($request);

    /**
     * Remove product from storage
     *
     * @param \App\Models\Product $product
     * @return Object $product
     */
    public function delete($product);

    /**
     * Update product price for warehouse shop product table in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function updatePrice($request);

    /**
     * Get Product List
     *
     * @return Product $productList
     */
    public function getProductListwithPriceByShopId($shopId);

    /**
     * Get last product lists
     *
     * @return Object $productList
     */
    public function getLastFiveProductList();

    /**
     * Insert multiple product in storage
     *
     * @param  Array $data
     * @param  Object $category
     * @return Object
     */
    public function insertMultiProduct($data, $category);

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
