<?php

namespace App\Services;

use App\Contracts\Dao\CategoryDaoInterface;
use App\Contracts\Dao\ShopDaoInterface;
use App\Contracts\Services\CategoryServiceInterface;
use Auth;

class CategoryService implements CategoryServiceInterface
{
    private $categoryDao;
    private $shopDao;
    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\CategoryDaoInterface $categoryDao
     * @param \App\Contracts\Dao\ShopDaoInterface $shopDao

     * @return void
     */
    public function __construct(CategoryDaoInterface $categoryDao, ShopDaoInterface $shopDao)
    {
        $this->categoryDao = $categoryDao;
        $this->shopDao = $shopDao;
    }

    /**
     * Get category list with parent category name
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $categorylist
     */
    public function getCategoryList($request)
    {
        return $this->categoryDao->getCategoryList($request);
    }

    /**
     * Get category list with parent category name
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $categorylist
     */
    public function getAllCategoryList()
    {
        return $this->categoryDao->getAllCategoryList();
    }

    /**
     * Get all category list
     *
     * @return Object $productCategoryList
     */
    public function getProductCategoryList()
    {
        return $this->categoryDao->getProductCategoryList();
    }

    /**
    * Get all category list by shop
    *
    * @param  Integer $shop_id
    * @return Object $productCategoryList
    */
    public function getProductCategoryListByShop($shop_id)
    {
        return $this->categoryDao->getProductCategoryListByShop($shop_id);
    }

    /**
     * Get sub category lists search by parent category id
     *
     * @param  Integer $categoryId
     * @return Object $productSubCategoryList
     */
    public function getProductSubCategoryListByParentCategoryId($categoryId)
    {
        return $this->categoryDao->getProductSubCategoryListByParentCategoryId($categoryId);
    }

    /**
     * Get all parent category list
     *
     * @return Object $parentCategoryList
     */
    public function getParentCategoryList()
    {
        return $this->categoryDao->getParentCategoryList();
    }

    /**
     * Get count for sub categories search by parent category id
     *
     * @param  Integer $categoryId
     * @return Integer $childCategoryList
     */
    public function getChildCountByCategoryId($categoryId)
    {
        return $this->categoryDao->getChildCountByCategoryId($categoryId);
    }

    /**
     * Get all parent category list that does not include search category
     *
     * @param  Integer $categoryId
     * @return Object $parentCategoryList
     */
    public function getParentCategoryListExceptCurrentParent($categoryId)
    {
        return $this->categoryDao->getParentCategoryListExceptCurrentParent($categoryId);
    }

    /**
     * Get category info search by category id
     *
     * @param  Integer $product_category_id
     * @return Object $category
     */
    public function getCategoryById($product_category_id)
    {
        return $this->categoryDao->getCategoryById($product_category_id);
    }

    /**
     * Store category info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $category
     */
    public function insert($request)
    {
        // Check shop id is owned for company admin role
        if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
            $shopList = $this->shopDao->getShopTypeByCompanyID(Auth::guard('staff')->user()->company_id);
            foreach ($request->shop_id as $value) {
                $checkExists=$shopList->contains('id', $value);
                if (! $checkExists) {
                    return $checkExists;
                }
            }
        }
        // Check shop id is owned for shop admin role
        if (Auth::guard('staff')->user()->role == config('constants.SHOP_ADMIN')) {
            if (! in_array(Auth::guard('staff')->user()->shop_id, $request->shop_id)) {
                return false;
            }
        }
        return $this->categoryDao->insert($request);
    }

    /**
     * Update category info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Category $category
     * @return Object $category
     */
    public function update($request, $category)
    {
        // Check shop id is owned for company admin role
        if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
            $shopList = $this->shopDao->getShopTypeByCompanyID(Auth::guard('staff')->user()->company_id);
            foreach ($request->shop_id as $value) {
                $checkExists=$shopList->contains('id', $value);
                if (! $checkExists) {
                    return $checkExists;
                }
            }
        }
        // Check shop id is owned for shop admin role
        if (Auth::guard('staff')->user()->role == config('constants.SHOP_ADMIN')) {
            if (! in_array(Auth::guard('staff')->user()->shop_id, $request->shop_id)) {
                return false;
            }
        }
        return $this->categoryDao->update($request, $category);
    }

    /**
     * Remove specified category in storage
     *
     * @param  \App\Models\Category $category
     * @return Object $category
     */
    public function delete($category)
    {
        return $this->categoryDao->delete($category);
    }

    /**
     * Get category search by name
     *
     * @param  string $name
     * @return Object $category
     */
    public function getCategoryByName($category_name)
    {
        return $this->categoryDao->getCategoryByName($category_name);
    }

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getInventoryStockCategoryDataExport($request)
    {
        return $this->categoryDao->getInventoryStockCategoryDataExport($request);
    }
}
