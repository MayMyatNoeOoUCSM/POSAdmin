<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\CategoryServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductListByCategoryIDRequest;
use App\Http\Requests\Api\ProductSearchByProductCodeRequest;
use App\Http\Requests\Api\ProductSearchRequest;
use App\Http\Resources\CategoryListResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends ApiController
{
    private $productService;
    private $categoryService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\ProductServiceInterface $productService
     * @param \App\Contracts\Services\CategoryServiceInterface $categoryService
     *
     * @return void
     */
    public function __construct(ProductServiceInterface $productService, CategoryServiceInterface $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    /**
     * Search Product
     *
     * @param  App\Http\Requests\Api\ProductSearchRequest $request
     * @return Collection
     */
    public function searchProduct(ProductSearchRequest $request)
    {
        $productList = $this->productService->getSearchProduct($request);
        return ProductListResource::collection($productList)->additional(
            ['status'=>'success',
                'message'=>'Successfully retrieved search product list']
        );
    }

    /**
     * Product Category List
     *
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function productCategoryList(Request $request)
    {
        $productCategoryList = $this->categoryService->getProductCategoryListByShop(Auth::user()->shop_id);
        return CategoryListResource::collection($productCategoryList)->additional(
            ['status'=>'success',
                'message'=>'Successfully retrieved product category list']
        );
    }

    /**
     * Product List By Category ID
     *
     * @param  App\Http\Requests\Api\ProductListByCategoryIDRequest $request
     * @return Collection
     */
    public function productListByCategoryID(ProductListByCategoryIDRequest $request)
    {
        $productList = $this->productService->getProductListByCategoryID($request);
        return ProductListResource::collection($productList)->additional(
            ['status'=>'success',
                'message'=>'Successfully retrieved product list by category id']
        );
    }

    /**
     * Search Product By Product Code
     *
     * @param  App\Http\Requests\Api\ProductSearchByProductCodeRequest $request
     * @return Resource
     */
    public function searchProductByCode(ProductSearchByProductCodeRequest $request)
    {
        $product = $this->productService->getProductByProductCode($request);
        if ($product) {
            return new ProductResource($product);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Product code does not exist.',
        ]);
    }
}
