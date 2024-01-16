<?php

namespace App\Dao;

use App\Contracts\Dao\CategoryDaoInterface;
use App\Models\Category;
use App\Models\Shop;
use App\Models\WarehouseShopProductRel;
use Illuminate\Support\Facades\Auth;

/**
 * Category Dao
 *
 * @author
 */
class CategoryDao implements CategoryDaoInterface
{
    /**
     * Get category list with parent category name
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $categorylist
     */
    public function getCategoryList($request)
    {
        $categoryList = Category::leftJoin('m_category as c', 'c.id', '=', 'm_category.parent_category_id')
            ->where('m_category.is_deleted', config('constants.DEL_FLG_OFF'));
        if ($request->search_parent_category_id) {
            $categoryList = $categoryList->where('m_category.parent_category_id', $request->search_parent_category_id);
        }
        $categoryList = $categoryList->select('m_category.*', 'c.name as parent_category_name');
        $categoryList = $categoryList->paginate($request->custom_pg_size == "" ? config('constants.CATEGORY_PAGINATION') : $request->custom_pg_size);
        return $categoryList;
    }

    /**
     * Get category list with parent category name
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $categorylist
     */
    public function getAllCategoryList()
    {
        $categoryList = Category::where('is_deleted', config('constants.DEL_FLG_OFF'))->get();
        return $categoryList;
    }

    /**
     * Get all category list
     *
     * @return Object $productCategoryList
     */
    public function getProductCategoryList()
    {
        $productCategoryList = Category::
        where('m_category.is_deleted', config('constants.DEL_FLG_OFF'));
       
        $productCategoryList = $productCategoryList
            ->select('m_category.id', 'm_category.name')
            ->get();
        return $productCategoryList;
    }

    /**
     * Get all category list by shop
     *
     * @param  Integer $shop_id
     * @return Object $productCategoryList
     */
    public function getProductCategoryListByShop($shop_id)
    {
        $productCategoryList = Category::join('m_shop_category', 'm_shop_category.category_id', '=', 'm_category.id')
            ->where('m_shop_category.shop_id', $shop_id)
            ->where('m_category.is_deleted', config('constants.DEL_FLG_OFF'))
            ->select('m_category.id', 'm_category.name')
            ->get();
        return $productCategoryList;
    }

    /**
     * Get sub category lists search by parent category id
     *
     * @param  Integer $categoryId
     * @return Object $productSubCategoryList
     */
    public function getProductSubCategoryListByParentCategoryId($categoryId)
    {
        $productSubCategoryList = Category::where('is_deleted', config('constants.DEL_FLG_OFF'))->where('parent_category_id', $categoryId)->get();
        return $productSubCategoryList;
    }

    /**
     * Get all parent category list
     *
     * @return Object $parentCategoryList
     */
    public function getParentCategoryList()
    {
        $parentCategoryList = Category::where('parent_category_id', null)->orWhere('parent_category_id', "=", 0)
            ->where('is_deleted', config('constants.DEL_FLG_OFF'))->get();
        return $parentCategoryList;
    }

    /**
     * Get count for sub categories search by parent category id
     *
     * @param  Integer $categoryId
     * @return Integer $childCategoryList
     */
    public function getChildCountByCategoryId($categoryId)
    {
        $childCategoryList = Category::where('parent_category_id', $categoryId)
            ->where('is_deleted', config('constants.DEL_FLG_OFF'))->count();
        return $childCategoryList;
    }

    /**
     * Get all parent category list that does not include search category
     *
     * @param  Integer $categoryId
     * @return Object $parentCategoryList
     */
    public function getParentCategoryListExceptCurrentParent($categoryId)
    {
        $parentCategoryList = Category::where('m_category.parent_category_id', null)->orWhere('m_category.parent_category_id', "=", 0)
            ->where('m_category.id', '!=', $categoryId)
            ->where('m_category.is_deleted', config('constants.DEL_FLG_OFF'))->get();
        return $parentCategoryList;
    }

    /**
     * Get category info search by category id
     *
     * @param  Integer $product_category_id
     * @return Object $category
     */
    public function getCategoryById($product_category_id)
    {
        $category = Category::where('m_category.id', $product_category_id)
            ->where('is_deleted', config('constants.DEL_FLG_OFF'))->first();
        return $category;
    }

    /**
     * Store category info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $category
     */
    public function insert($request)
    {
        $category = new Category;
        $category->company_id = Auth::user()->company_id;
        $category->name = $request->name;
        $category->parent_category_id = $request->parent_category_id;
        $category->description = $request->description;
        $category->is_deleted = config('constants.DEL_FLG_OFF');
        $category->create_user_id = auth()->user()->id;
        $category->update_user_id = auth()->user()->id;
        $category->create_datetime = Date('Y-m-d H:i:s');
        $category->update_datetime = Date('Y-m-d H:i:s');
        $category->save();

        $shop = Shop::whereIn('id', $request->shop_id)->get();
        $category->shop()->attach($shop);
        return $category;
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
        $category->name = $request->name;
        $category->parent_category_id = $request->parent_category_id;
        $category->description = $request->description;
        $category->update_user_id = auth()->user()->id;
        $category->update_datetime = Date('Y-m-d H:i:s');
        // $category->is_deleted = config('constants.DEL_FLG_OFF');

        $category->save();

        // $category->shop()->detach();
        $shop = Shop::whereIn('id', $request->shop_id)->get();
        $category->shop()->sync($shop);

        return $category;
    }

    /**
     * Remove specified category in storage
     *
     * @param  \App\Models\Category $category
     * @return Object $category
     */
    public function delete($category)
    {
        $category = Category::where('id', $category->id)->update(['is_deleted' => config('constants.DEL_FLG_ON')]);
        return $category;
    }

    /**
     * Get category search by name
     *
     * @param  string $name
     * @return Object $category
     */
    public function getCategoryByName($name)
    {
        $category = Category::where('name', $name)
            ->where('is_deleted', config('constants.DEL_FLG_OFF'))->first();
        return $category;
    }

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getInventoryStockCategoryDataExport($request)
    {
        $stockCategory = WarehouseShopProductRel::join("m_shop", "m_shop.id", "=", "t_warehouse_shop_product.shop_id")
            ->join("m_product", "m_product.id", "=", "t_warehouse_shop_product.product_id")
            ->join("m_category", "m_category.id", "=", "m_product.product_type_id")
            ->select(\DB::raw("m_shop.name as shop_name,m_category.name as category_name, sum(t_warehouse_shop_product.quantity) as stock_quantity"))
            ->where(function ($q) use ($request) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }
            })
            ->groupBy("shop_name", "category_name")
            ->get();
        return $stockCategory;
    }
}
