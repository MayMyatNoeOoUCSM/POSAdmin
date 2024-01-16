<?php

namespace App\Contracts\Services;

interface CategoryServiceInterface
{

    /**
     * Get category list with parent category name
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $categorylist
     */
    public function getCategoryList($request);

    /**
     * Get category list with parent category name
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $categorylist
     */
    public function getAllCategoryList();

    /**
     * Get all category list
     *
     * @return Object $productCategoryList
     */
    public function getProductCategoryList();

    /**
    * Get all category list by shop
    *
    * @param  Integer $shop_id
    * @return Object $productCategoryList
    */
    public function getProductCategoryListByShop($shop_id);

    /**
     * Get sub category lists search by parent category id
     *
     * @param  Integer $categoryId
     * @return Object $productSubCategoryList
     */
    public function getProductSubCategoryListByParentCategoryId($categoryId);

    /**
     * Get all parent category list
     *
     * @return Object $parentCategoryList
     */
    public function getParentCategoryList();

    /**
     * Get count for sub categories search by parent category id
     *
     * @param  Integer $categoryId
     * @return Integer $childCategoryList
     */
    public function getChildCountByCategoryId($categoryId);

    /**
     * Get all parent category list that does not include search category
     *
     * @param  Integer $categoryId
     * @return Object $parentCategoryList
     */
    public function getParentCategoryListExceptCurrentParent($categoryId);

    /**
     * Get category info search by category id
     *
     * @param  Integer $product_category_id
     * @return Object $category
     */
    public function getCategoryById($product_category_id);

    /**
     * Store category info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $category
     */
    public function insert($request);

    /**
     * Update category info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Category $category
     * @return Object $category
     */
    public function update($request, $category);

    /**
     * Remove specified category in storage
     *
     * @param  \App\Models\Category $category
     * @return Object $category
     */
    public function delete($category);

    /**
     * Get category search by name
     *
     * @param  string $name
     * @return Object $category
     */
    public function getCategoryByName($category_name);

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getInventoryStockCategoryDataExport($request);
}
