<?php

namespace App\Services;

use App\Contracts\Dao\ProductDaoInterface;
use App\Contracts\Dao\ShopDaoInterface;
use App\Contracts\Dao\StaffDaoInterface;
use App\Contracts\Services\ProductServiceInterface;
use Auth;
use Illuminate\Support\Facades\File;
use Storage;

class ProductService implements ProductServiceInterface
{
    private $productDao;
    private $staffDao;
    private $shopDao;


    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\ProductDaoInterface $productDao
     * @param \App\Contracts\Dao\StaffDaoInterface $staffDao
     * @param \App\Contracts\Dao\ShopDaoInterface $shopDao

     *
     * @return void
     */
    public function __construct(ProductDaoInterface $productDao, StaffDaoInterface $staffDao, ShopDaoInterface $shopDao)
    {
        $this->productDao = $productDao;
        $this->staffDao = $staffDao;
        $this->shopDao = $shopDao;
    }

    /**
     * Get product list and category name
     *
     * @return Object
     */
    public function getProductCategoryList()
    {
        return $this->productDao->getProductCategoryList();
    }

    /**
     * Get product list search by product id, product sub category or product category
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $productList
     */
    public function getProductListByCategory($request)
    {
        return $this->productDao->getProductListByCategory($request);
    }

    /**
     * Get product list and product price
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $productList
     */
    public function getProductListByCategoryWarehouseShop($request)
    {
        return $this->productDao->getProductListByCategoryWarehouseShop($request);
    }

    /**
     * Get product list
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $productList
     */
    public function getProductList($request)
    {
        return $this->productDao->getProductList($request);
    }

    /**
     * Get all product list
     *
     * @return Object $productList
     */
    public function getAllProductList()
    {
        return $this->productDao->getAllProductList();
    }

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getProductDataExport($request)
    {
        return $this->productDao->getProductDataExport($request);
    }

    /**
     * Get product list and product price search by shop id
     *
     * @param  Integer $shopId
     * @return Object $productList
     */
    public function getProductListwithPriceByShopId($shopId)
    {
        return $this->productDao->getProductListwithPriceByShopId($shopId);
    }

    /**
     * Get product list that have stock
     *
     * @return Object $productList
     */
    public function getStockListByStock()
    {
        return $this->productDao->getStockListByStock();
    }

    /**
     * Get total product count search by category
     *
     * @param Integer $categoryId
     * @return Integer
     */
    public function getProductCountByCategoryId($categoryId)
    {
        return $this->productDao->getProductCountByCategoryId($categoryId);
    }

    /**
     * Store product info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param Object $category
     * @param Integer $product_id
     * @return Object $product
     */
    public function insert($request, $category, $product_id)
    {
        // last product search by category
        $lastProduct = $this->productDao->getLastProduct($category);
        //dd($lastProduct);
        $productCode = "0001";
        $subCategoryId = "000";
        $categoryId = "00";
        $companyId  = "00";
        if ($lastProduct != " ") {
            if ($lastProduct) {
                $productCode = substr($lastProduct->product_code, -4, 4) + 1;
                $productCode = str_pad($productCode, 4, '0', STR_PAD_LEFT);
            }
            if (is_null($category->parent_category_id)) {
                $categoryId = str_pad($category->id, 2, '0', STR_PAD_LEFT);
            } else {
                $subCategoryId = str_pad($category->id, 3, '0', STR_PAD_LEFT);
                $categoryId = str_pad($category->parent_category_id, 2, '0', STR_PAD_LEFT);
            }
        } else {
            $categoryId = str_pad($category->id, 2, '0', STR_PAD_LEFT);
        }
        $companyId = str_pad(Auth::user()->company_id, 2, '0', STR_PAD_LEFT);
        // Image Upload
        if (! empty($request->image)) { // New Image
            $image = $request->file('image');
            $name = $productCode . '.' . $image->getClientOriginalExtension();
            $destinationPath =  env('PRODUCT_PATH') . "/" . $companyId ."/" . $categoryId . "/" . $subCategoryId;
           
            $image->move($destinationPath, $name);
           
            $request->image = env('PRODUCT_PATH') . "/" . $companyId . "/" . $categoryId . "/" . $subCategoryId . "/" . $name;
        } /**
        elseif (! empty($request->old_image) and empty($request->image)) { // Old Image Move
            $path = config('constants.PRODUCT_PATH') . "/" . $categoryId . "/" . $subCategoryId . "/";
            if (! File::exists($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $extension = substr($request->old_image, strpos($request->old_image, ".") + 1);
            $im = imagecreatetruecolor(120, 20);
            $text_color = imagecolorallocate($im, 233, 14, 91);
            imagestring($im, 1, 5, 5, 'A Simple Text String', $text_color);

            imagejpeg($im, $path . $productCode . '.' . $extension);
            File::copy(env('PRODUCT_PATH') . "/" . $request->old_image, $path . $productCode . '.' . $extension);
            $request->image = env('PRODUCT_PATH') . "/" . $categoryId . "/" . $subCategoryId . "/" . $productCode . '.' . $extension;
        }
        */

        
        // merge country code,category id,sub category id and product code
        $product_code = env('COUNTRY_CODE') . $companyId. $categoryId . $subCategoryId . $productCode;

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
        
        // store product info in storage
        $result = $this->productDao->insert($request, $product_code, $product_id);
        // check return statment and send notification
        if ($result) {
            // Insert new product notification
            $staffList = \App\Models\Staff::select('id')
                ->where('company_id', Auth::guard('staff')->user()->company_id)
                ->where('role', config('constants.COMPANY_ADMIN'))
                ->withoutGlobalScopes()->get();

            $messageInfo = [
                'body' => 'New Product ('.$result->name.')',
                'type' => config('constants.NOTIFICATION_NEW_PRODUCTS'),
            ];
            foreach ($staffList as $key => $value) {
                $staff = \App\Models\Staff::withoutGlobalScopes()->find($value->id);
                $staff->notify(new \App\Notifications\AdminNotification($messageInfo));
            }
            return true;
        }
        return false;
    }

    /**
     * Update product info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @param Object $category
     * @return Object $product
     */
    public function update($request, $product, $category)
    {
        // check old category and new category
        if ($request->product_category_id == $product->product_type_id) {
            // upload new image
            if (! empty($request->image)) {
                if (! is_null($request->old_image) && file_exists($request->old_image)) {
                    unlink($request->old_image);
                }
                $productCode = substr($product->product_code, -4, 4);
                $companyId = substr($product->product_code, 3, 2);
                $categoryId = substr($product->product_code, 5, 2);
                $subCategoryId = substr($product->product_code, 7, 3);
                
                $image = $request->file('image');
                $name = $productCode . '.' . $image->getClientOriginalExtension();
                $destinationPath = env('PRODUCT_PATH') . "/". $companyId. "/" . $categoryId . "/" . $subCategoryId;
                $image->move($destinationPath, $name);
                $request->image = env('PRODUCT_PATH') . "/". $companyId. "/" . $categoryId . "/" . $subCategoryId . "/" . $name;
            }

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

            return $this->productDao->update($request, $product);
        } // old category and new category are not same, create new product
        //return $this->insert($request, $category, $product->id);
    }

    

    /**
     * Update minimun quantity for product in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function changeMinQty($request)
    {
        return $this->productDao->changeMinQty($request);
    }

    /**
     * Remove product from storage
     *
     * @param \App\Models\Product $product
     * @return Object $product
     */
    public function delete($product)
    {
        return $this->productDao->delete($product);
    }

    /**
     * Update product price for warehouse shop product table in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function updatePrice($request)
    {
        return $this->productDao->updatePrice($request);
    }

    /**
     * Get last product lists
     *
     * @return Object $productList
     */
    public function getLastFiveProductList()
    {
        return $this->productDao->getLastFiveProductList();
    }

    /**
     * Insert multiple product in storage
     *
     * @param  Array $data
     * @param  Object $category
     * @return Object
     */
    public function insertMultiProduct($data, $category)
    {
        $lastProduct = $this->productDao->getLastProduct($category);
        $productCode = "0001";
        $subCategoryId = "000";
        $categoryId = "00";
        $companyId  = "00";
        if ($lastProduct != " ") {
            if ($lastProduct) {
                $productCode = substr($lastProduct->product_code, -4, 4) + 1;
                $productCode = str_pad($productCode, 4, '0', STR_PAD_LEFT);
            }
            if (is_null($category->parent_category_id)) {
                $categoryId = str_pad($category->id, 2, '0', STR_PAD_LEFT);
            } else {
                $subCategoryId = str_pad($category->id, 3, '0', STR_PAD_LEFT);
                $categoryId = str_pad($category->parent_category_id, 2, '0', STR_PAD_LEFT);
            }
        } else {
            $categoryId = str_pad($category->id, 2, '0', STR_PAD_LEFT);
        }
        $companyId  = str_pad(Auth::user()->company_id, 2, '0', STR_PAD_LEFT);
        $product_code = env('COUNTRY_CODE') . $companyId . $categoryId . $subCategoryId . $productCode;
        return $this->productDao->insertMultiProduct($data, $product_code);
    }

    /**
     * Get product list by search name
     *
     * @param \Illuminate\Http\ProductSearchRequest $request
     * @return Object $productList
     */
    public function getSearchProduct($request)
    {
        return $this->productDao->getSearchProduct($request);
    }

    /**
     * Get product list by category id
     *
     * @param \Illuminate\Http\ProductListByCategoryIDRequest $request
     * @return Object $productList
     */
    public function getProductListByCategoryID($request)
    {
        return $this->productDao->getProductListByCategoryID($request);
    }

    /**
     * Get product info by product code
     *
     * @param \Illuminate\Http\ProductSearchByProductCodeRequest $request
     * @return Object $product
     */
    public function getProductByProductCode($request)
    {
        return $this->productDao->getProductByProductCode($request);
    }

    /**
     * Check product exists check by product_id array
     *
     * @param  Array $product_array
     * @return Boolean
     */
    public function checkProductArrayExists($product_array)
    {
        return $this->productDao->checkProductArrayExists($product_array);
    }

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getInventoryStockProductDataExport($request)
    {
        return $this->productDao->getInventoryStockProductDataExport($request);
    }
}
