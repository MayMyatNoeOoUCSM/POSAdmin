<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CategoryServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Contracts\Services\ShopServiceInterface;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $categoryService;
    private $productService;
    private $shopService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\CategoryServiceInterface $categoryService
     * @param \App\Contracts\Services\ProductServiceInterface $productService
     * @param \App\Contracts\Services\ShopServiceInterface $shopService
     * @return void
     */
    public function __construct(CategoryServiceInterface $categoryService, ShopServiceInterface $shopService, ProductServiceInterface $productService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->shopService = $shopService;
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
        //category list and parent category list
        $categoryList = $this->categoryService->getCategoryList($request);
        $parentCategoryList = $this->categoryService->getParentCategoryList($request);
        return view('category.index', compact(['categoryList', 'parentCategoryList']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // parent category list
        $categoryList = $this->categoryService->getParentCategoryList();
        $shopCategoryList = $this->shopService->getShopDetailList();
        return view('category.create', compact('categoryList', 'shopCategoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest CategoryRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        // store category info in storage and check return statement
        $result = $this->categoryService->insert($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Category']));
            return redirect()->route('category');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        // when try to update parent category, parent category can't choose itself
        $categoryList = $this->categoryService->getParentCategoryListExceptCurrentParent($category->id);
        $shopCategoryList = $this->shopService->getShopDetailList();
        if (is_null($categoryList)) {
            return back()->with('error_status', __('message.E0001'));
        }

        $shop_id_array = [];
        foreach ($category->shop as $shop) {
            $shop_id_array[] = $shop->pivot->shop_id;
        }
        return view('category.edit', compact('category', 'categoryList', 'shopCategoryList', 'shop_id_array'));
    }

    /**
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request\CategoryRequest  $request
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        // update category info in storage and check return statement
        $result = $this->categoryService->update($request, $category);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'Category']));
            return redirect()->route('category');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function delete(Category $category)
    {
        // check related product count and child count for category
        $productCount = $this->productService->getProductCountByCategoryId($category->id);
        $childCategoryCount = 0;
        if (empty($category->parent_category_id)) {
            $childCategoryCount = $this->categoryService->getChildCountByCategoryId($category->id);
        }
        if ($productCount > 0 || $childCategoryCount > 0) {
            return back()->with('error_status', __('message.W0002'));
        }

        // remove category info in storage and check return statement
        $category = $this->categoryService->delete($category);
        if ($category) {
            return redirect()->route('category')->with('success_status', __('message.I0003', ["tbl_name" => 'Category']));
        }
        return back()->with('error_status', __('message.E0001'));
    }
}
