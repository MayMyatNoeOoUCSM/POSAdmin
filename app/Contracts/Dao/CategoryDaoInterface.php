<?php

namespace App\Contracts\Dao;

/**
 * CategoryDaoInterface
 */
interface CategoryDaoInterface
{

    /**
     * Get category list from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object categoryList
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
     * Get product category list from storage
     *
     * @return Object categoryList
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
     * Get Product Sub Category List By Parent Category Id from storage
     *
     * @param Integer $categoryId
     * @return Object $productCategoryList
     */
    public function getProductSubCategoryListByParentCategoryId($categoryId);

    /**
     * Get Parent Category List from storage
     *
     * @return Object parentCategoryList
     */
    public function getParentCategoryList();

    /**
     * Get Child Category from storage
     *
     * @param Integer $categoryId
     * @return Object childCategoryList
     */
    public function getChildCountByCategoryId($categoryId);

    /**
     * Get Parent Category except current parent id from storage
     *
     * @param Integer $categoryId
     * @return Object parentCategoryList
     */
    public function getParentCategoryListExceptCurrentParent($categoryId);

    /**
     * Get Category by Id from storage
     *
     * @param Integer $product_category_id
     * @return Object category
     */
    public function getCategoryById($product_category_id);

    /**
     * Category data is saved into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $category
     */
    public function insert($request);

    /**
     * Category data is update into storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \app\Models\Category $category
     * @return Object $category
     */
    public function update($request, $category);

    /**
     * Delete Category By Id from storage
     *
     * @param \app\Models\Category $category
     * @return Object $category
     */
    public function delete($category);

    /**
     * Get category by category name from storage
     * @param  String $category_name
     * @return Object category
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
