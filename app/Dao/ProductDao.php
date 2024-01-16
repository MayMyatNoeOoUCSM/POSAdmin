<?php

namespace App\Dao;

use App\Contracts\Dao\ProductDaoInterface;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\WarehouseShopProductRel;
use Auth;
use Illuminate\Support\Facades\DB;

/**
 * Product Dao
 *
 * @author
 */
class ProductDao implements ProductDaoInterface
{
    /**
     * Get product list and category name
     *
     * @return Object $productList
     */
    public function getProductCategoryList()
    {
        $productList = Product::leftJoin('m_category as c', 'c.id', '=', 'm_product.product_type_id')
            ->select('m_product.*', 'c.name as category_name')
            ->where('m_product.product_status', '!=', config('constants.DELETED'))
            ->distinct('c.id')
            ->get();
        return $productList;
    }

    /**
     * Get product list search by product id, product sub category or product category
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $productList
     */
    public function getProductListByCategory($request)
    {
        $productList = Product::leftJoin('m_category as c', 'c.id', '=', 'm_product.product_type_id')
            ->select('m_product.*')
            ->where('m_product.product_status', '!=', config('constants.DELETED'));
        // product id
        if ($request->productId) {
            $productList = $productList->where('m_product.id', $request->productId);
        } else {
            // product sub category
            if ($request->productSubCategory) {
                $productList = $productList->where('c.id', $request->productSubCategory);
            // product category
            } elseif ($request->productCategory) {
                $productList = $productList->where('c.id', $request->productCategory)->orWhere('c.parent_category_id', $request->productCategory);
            }
        }
        $productList = $productList->get();
        return $productList;
    }

    /**
     * Get product list and product price
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $productList
     */
    public function getProductListByCategoryWarehouseShop($request)
    {
        $productList = Product::leftJoin('m_category as c', 'c.id', '=', 'm_product.product_type_id')
            ->leftjoin('t_warehouse_shop_product', 't_warehouse_shop_product.product_id', '=', 'm_product.id')
            ->select('m_product.*', 't_warehouse_shop_product.price as unit_price')
            ->where('m_product.product_status', '!=', config('constants.DELETED'));
        // warehouse
        if (! empty($request->warehouse_id)) {
            $productList = $productList->where('t_warehouse_shop_product.warehouse_id', '=', $request->warehouse_id);
        }
        // shop
        if (! empty($request->shop_id)) {
            $productList = $productList->where('t_warehouse_shop_product.shop_id', '=', $request->shop_id);
        }
        // product
        if ($request->productId) {
            $productList = $productList->where('m_product.id', $request->productId);
        } else {
            // product sub category
            if ($request->productSubCategory) {
                $productList = $productList->where('c.id', $request->productSubCategory);
            // product category
            } elseif ($request->productCategory) {
                $productList = $productList->where('c.id', $request->productCategory)->orWhere('c.parent_category_id', $request->productCategory);
            }
        }
        $productList = $productList->get();
        return $productList;
    }

    /**
     * Get product list
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $productList
     */
    public function getProductList($request)
    {
        $productList = Product::leftJoin('m_category as c', 'c.id', '=', 'm_product.product_type_id')
            ->leftJoin('t_warehouse_shop_product', 't_warehouse_shop_product.product_id', '=', 'm_product.id')
            ->where('m_product.product_status', '!=', config('constants.DELETED'));
        // product name
        if ($request->search_name) {
            $productList = $productList->whereRaw("LOWER(m_product.name) like LOWER('%" . $request->search_name . "%')");
        }
        // product category
        if ($request->search_category) {
            $productList = $productList->where('m_product.product_type_id', $request->search_category);
        }
        $productList = $productList->select(DB::raw('m_product.*,c.name as category_name,t_warehouse_shop_product.price as price'));
        // sorting
        if ($request->sorting_column) {
            $productList = $productList->orderBy($request->sorting_column, $request->sorting_order);
        } else {
            $productList = $productList->orderBy('m_product.create_datetime', 'desc');
        }
        $productList = $productList->groupBy('m_product.id', 'c.name', 't_warehouse_shop_product.price');
        $productList = $productList->paginate($request->custom_pg_size == "" ? config('constants.PRODUCT_PAGINATION') : $request->custom_pg_size)->onEachSide(2);

        return $productList;
    }

    /**
     * Get all product list
     *
     * @return Object or null
     */
    public function getAllProductList()
    {
        $productList = Product::where('m_product.product_status', '!=', config('constants.DELETED'))->get();
        return $productList;
    }

    /**
     * Get product list and product price search by shop id
     *
     * @param  Integer $shopId
     * @return Object $productList
     */
    public function getProductListwithPriceByShopId($shopId)
    {
        $productList = Product::leftjoin(
            't_warehouse_shop_product as wsp',
            function ($join) {
                $join->on('m_product.id', '=', 'wsp.product_id');
            }
        )
            ->where('m_product.product_status', '!=', config('constants.DELETED'))
            ->where('wsp.shop_id', '=', $shopId)
            ->select('m_product.id', 'm_product.name', 'wsp.price as product_price')
            ->get();
        return $productList;
    }

    /**
     * Get product list that have stock
     *
     * @return Object $productList
     */
    public function getStockListByStock()
    {
        $productList = WarehouseShopProductRel::leftJoin('m_product as p', 'p.id', '=', 't_warehouse_shop_product.product_id')
            ->select('p.id as product_id', 'p.name as product_name')->groupBy('p.id')->get();
        return $productList;
    }

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getProductDataExport($request)
    {
        $product = ShopProduct::join("m_shop as s", "s.id", "=", "m_shop_product.shop_id")
            ->leftJoin("m_product as p", "p.id", "=", "m_shop_product.product_id")
            ->leftJoin("t_warehouse_shop_product as wsp", "wsp.product_id", "=", "m_shop_product.product_id")
            ->select('s.name as shop_name', 'p.name as product_name', 'wsp.quantity as stock_quantity')
            ->where(function ($q) use ($request) {
                if (! empty($request->select_shop_name)) {
                    $q->where("m_shop_product.shop_id", "=", $request->select_shop_name);
                }
            })->get();
        $product->header = ["Shop Name", "Product Name", "Stock Quantity"];
        return $product;
    }

    /**
     * Get total product count search by category
     *
     * @param Integer $categoryId
     * @return Integer
     */
    public function getProductCountByCategoryId($categoryId)
    {
        $productCount = Product::where('product_type_id', $categoryId)
            ->where('product_status', '!=', config('constants.DELETED'))->count();
        return $productCount;
    }

    /**
     * Get last product resource from storage
     * @param  \App\Models\Category $category
     * @return Object
     */
    public function getLastProduct($category)
    {
        if ($category->parent_category_id == null) {
            $product = Product::where("product_type_id", $category->id)->orderBy("m_product.id", "DESC")->withoutGlobalScopes()->first();
        } else {
            $category_array = Category::select("id")->where('parent_category_id', "=", $category->parent_category_id)
                ->get()->toArray();
            array_push($category_array, $category->parent_category_id);
            $product = Product::whereIn("product_type_id", $category_array)->orderBy("m_product.id", "DESC")->withoutGlobalScopes()->first();
        }
        return $product;
    }

    /**
     * Store product info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param String $product_code
     * @param Integer $product_id
     * @return Object $product
     */
    public function insert($request, $product_code, $product_id)
    {
        $product = new Product;
        if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
            $product->company_id = Auth::guard('staff')->user()->company_id;
        } else {
            $product->company_id = Shop::where('id', Auth::guard('staff')->user()->shop_id)
                ->first()->company_id;
        }
        $product->product_type_id = $request->product_category_id;
        $product->product_code = $product_code;

        // check barcode
        if ($request->barcode_check == 1) {
            $product->barcode = $product_code;
        } else {
            $product->barcode = $request->barcode ?? null;
        }
        $product->name = $request->name;
        $product->short_name = $request->short_name;
        $product->sale_price = $request->sale_price;
        $product->description = $request->description;
        $product->product_image_path = $request->image;
        $product->mfd_date = $request->mfd_date;
        $product->expire_date = $request->expire_date;
        $product->minimum_quantity = $request->min_qty;
        $product->product_status = $request->active ? config('constants.ACTIVE') : config('constants.IN_ACTIVE');
        $product->create_user_id = auth()->user()->id;
        $product->update_user_id = auth()->user()->id;
        $product->create_datetime = Date('Y-m-d H:i:s');
        $product->update_datetime = Date('Y-m-d H:i:s');
        $product->save();
        $shop = Shop::whereIn('id', $request->shop_id)->get();
        $product->shop()->attach($shop);

        //old product delete
        if ($product_id != 0) {
            $product = Product::find($product_id);
            $product->product_status = config('constants.DELETED');
            $product->update_user_id = auth()->user()->id;
            $product->update_datetime = Date('Y-m-d H:i:s');
            $product->save();
        }

        return $product;
    }

    /**
     * Update product info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return Object $product
     */
    public function update($request, $product)
    {
        //$product->product_type_id = $request->product_category_id;
        $product->barcode = $request->barcode ?? null;
        $product->name = $request->name;
        $product->short_name = $request->short_name;
        $product->sale_price = $request->sale_price;
        $product->description = $request->description;
        $product->product_image_path = ($request->image ?? $request->old_image);
        $product->mfd_date = $request->mfd_date;
        $product->expire_date = $request->expire_date;
        $product->product_status = $request->active ? config('constants.ACTIVE') : config('constants.IN_ACTIVE');
        $product->update_user_id = auth()->user()->id;
        $product->update_datetime = Date('Y-m-d H:i:s');
        $product->save();
        $shop = Shop::whereIn('id', $request->shop_id)->get();
        $product->shop()->sync($shop);
        return $product;
    }

    /**
     * Update minimun quantity for product in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function changeMinQty($request)
    {
        Product::where('id', $request->id)->update(['minimum_quantity' => $request->min_qty]);
        return true;
    }

    /**
     * Remove product from storage
     *
     * @param \App\Models\Product $product
     * @return Object $product
     */
    public function delete($product)
    {
        $product = Product::where('id', $product->id)->update(['product_status' => config('constants.DELETED')]);
        return $product;
    }

    /**
     * Update product price for warehouse shop product table in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function updatePrice($request)
    {
        WarehouseShopProductRel::where('product_id', $request->product_id)->update(['price' => $request->price]);
        return true;
    }

    /**
     * Get last product lists
     *
     * @return Object $productList
     */
    public function getLastFiveProductList()
    {
        $productList = Product::where('m_product.product_status', '!=', config('constants.DELETED'))->whereDate('m_product.create_datetime', Date('Y-m-d'))->orderBy('create_datetime', 'desc')->take(2)->get();
        return $productList;
    }

    /**
     * Get created product quantity for today
     *
     * @return Integer
     */
    public function getNewProductByToday()
    {
        $product = Product::whereDate('create_datetime', Date('Y-m-d'))->where('product_status', '!=', config('constants.DELETED'))->count();
        return $product;
    }

    /**
     * Insert multiple product in storage
     *
     * @param  Array $row
     * @param  String $product_code
     * @return Object $product
     */
    public function insertMultiProduct($row, $product_code)
    {
        $oldproduct = Product::leftJoin('m_category as c', 'c.id', '=', 'm_product.product_type_id')
            ->select('m_product.*', 'c.name as category_name')
            ->where('m_product.product_status', '!=', config('constants.DELETED'))
            ->where("c.name", "=", $row['category'])
            ->where("m_product.name", "=", $row['productname'])
            ->first();
        if ($oldproduct == null) {
            $product = new Product;
            if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
                $product->company_id = Auth::guard('staff')->user()->company_id;
            } else {
                $product->company_id = Shop::where('id', Auth::guard('staff')->user()->shop_id)
                    ->first()->company_id;
            }
            $product->product_type_id = Category::where("name", $row["category"])->first()->id ?? 0;
            if ($row["barcodecheck"] == 'generate') {
                $product->barcode = $row["barcode"] ?? null;
            } else {
                $product->barcode = "$product_code";
            }
            $product->product_code = "$product_code";
            $product->name = $row["productname"];
            $product->description = $row["description"];
            $product->sale_price = $row["saleprice"];
            $product->minimum_quantity = $row["minimumqty"];
            $product->mfd_date = ($row["mfddate"]!=''?date("Y-m-d", strtotime($row["mfddate"])): null);
            $product->expire_date = ($row["expireddate"]!=''?date("Y-m-d", strtotime($row["expireddate"])): null);
            $product->create_user_id = auth()->user()->id;
            $product->update_user_id = auth()->user()->id;
            $product->create_datetime = Date('Y-m-d H:i:s');
            $product->update_datetime = Date('Y-m-d H:i:s');
            $product->save();

            $shop = Shop::where('name', $row['shop'])->get();
            $product->shop()->sync($shop);
            return $product;
        }
    }

    /**
     * Get product details info search by product id
     *
     * @param  Integer $product_id
     * @return Object
     */
    public function details($product_id)
    {
        return Product::find($product_id);
    }

    /**
     * Get product list by search name
     *
     * @param \Illuminate\Http\ProductSearchRequest $request
     * @return Object $productList
     */
    public function getSearchProduct($request)
    {
        $productList = Product
            ::where('m_product.product_status', '!=', config('constants.DELETED'));

        // product name
        if ($request->search_name) {
            $productList = $productList->whereRaw("LOWER(m_product.name) like LOWER('%" . $request->search_name . "%')");
        }

        $productList = $productList->select(DB::raw('m_product.*'));

        // sorting
        if ($request->sorting_column) {
            $productList = $productList->orderBy($request->sorting_column, $request->sorting_order);
        } else {
            $productList = $productList->orderBy('m_product.create_datetime', 'desc');
        }
        //$productList = $productList->groupBy(['m_product.id']);
        $productList = $productList->paginate($request->custom_pg_size == "" ? config('constants.PRODUCT_PAGINATION') : $request->custom_pg_size);
        return $productList;
    }

    /**
     * Get product list by category id
     *
     * @param \Illuminate\Http\ProductListByCategoryIDRequest $request
     * @return Object $productList
     */
    public function getProductListByCategoryID($request)
    {
        $productList = Product
            ::leftJoin('m_category as c', 'c.id', '=', 'm_product.product_type_id')
                ->where('m_product.product_status', '!=', config('constants.DELETED'));
        $productList = $productList->select(DB::raw('m_product.*'));
        $productList = $productList->where('m_product.product_type_id', '=', $request->category_id);
        $productList = $productList->orderBy('m_product.create_datetime', 'desc');
        //$productList = $productList->groupBy('m_product.id');
        $productList = $productList->paginate(config('constants.PRODUCT_PAGINATION'));

        return $productList;
    }

    /**
     * Get product info by product code
     *
     * @param \Illuminate\Http\ProductSearchByProductCodeRequest $request
     * @return Object $product
     */
    public function getProductByProductCode($request)
    {
        $productList = Product
            ::leftJoin('m_category as c', 'c.id', '=', 'm_product.product_type_id')
                ->where('m_product.product_status', '!=', config('constants.DELETED'));
        $productList = $productList->select(DB::raw('m_product.*'));
        $productList = $productList->where('m_product.product_code', '=', $request->product_code);
        $productList = $productList->first();

        return $productList;
    }

    /**
     * Check product exists check by product_id array
     *
     * @param  Array $product_array
     * @return Boolean
     */
    public function checkProductArrayExists($product_array)
    {
        $product_list_count = Product::whereIn('m_product.id', $product_array)->get()->count();
        if (count($product_array) == $product_list_count) {
            return true;
        }
        return false;
    }

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getInventoryStockProductDataExport($request)
    {
        $stockProduct = WarehouseShopProductRel::join("m_product", "m_product.id", "=", "t_warehouse_shop_product.product_id")
            ->join("m_shop", "m_shop.id", "=", "t_warehouse_shop_product.shop_id")
            ->join("t_stock", "t_stock.product_id", "=", "m_product.id")
            ->select(\DB::raw("m_shop.name as shop_name,m_product.name as product_name, t_warehouse_shop_product.quantity as stock_quantity"))
            ->where(function ($q) use ($request) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }

                if (! empty($request->from_date) && ! empty($request->to_date)) {
                    $q->whereDate('t_stock.create_datetime', ">=", $request->from_date)
                      ->whereDate('t_stock.create_datetime', "<=", $request->to_date);
                }
            })
            ->groupBy("shop_name", "product_name", "stock_quantity")
            ->get();
        return $stockProduct;
    }
}
