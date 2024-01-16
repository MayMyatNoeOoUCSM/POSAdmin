<?php

namespace App\Dao;

use App\Contracts\Dao\StockDaoInterface;
use App\Models\ProductPriceHistory;
use App\Models\Stock;
use App\Models\WarehouseShopProductRel;

/**
 * Stock Dao
 *
 * @author
 */
class StockDao implements StockDaoInterface
{

    /**
     * Get Current Stock List
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $stockList
     */
    public function getCurrentStockList($request)
    {
        $stockList = WarehouseShopProductRel::leftJoin('m_product as p', 'p.id', '=', 't_warehouse_shop_product.product_id')
            ->leftJoin('m_warehouse as w', 'w.id', '=', 't_warehouse_shop_product.warehouse_id')
            ->leftJoin('m_shop as s', 's.id', '=', 't_warehouse_shop_product.shop_id');
        // product name
        if ($request->product_name) {
            $stockList = $stockList->whereRaw("LOWER(p.name) like LOWER('%" . $request->product_name . "%')");
        }
        // warehouse
        if ($request->warehouse_id) {
            $stockList = $stockList->where('t_warehouse_shop_product.warehouse_id', '=', $request->warehouse_id);
        }
        // shop
        if ($request->shop_id) {
            $stockList = $stockList->where('t_warehouse_shop_product.shop_id', '=', $request->shop_id);
        }
        // less stock
        if ($request->less_stock) {
            $stockList = $stockList->whereColumn('t_warehouse_shop_product.quantity', '<=', 't_warehouse_shop_product.minimum_quantity');
        }
        // order stock list
        if ($request->order_by_qty) {
            $stockList = $stockList->orderBy('t_warehouse_shop_product.quantity');
        }

        $stockList = $stockList->select('t_warehouse_shop_product.quantity', 't_warehouse_shop_product.minimum_quantity', 't_warehouse_shop_product.price', 'p.id as product_id', 'p.product_code as product_code', 'p.product_image_path as product_image', 'p.name as product_name', 'w.id as warehouse_id', 'w.name as warehouse_name', 's.id as shop_id', 's.name as shop_name');
        $stockList = $stockList->paginate($request->custom_pg_size == "" ? config('constants.STOCK_PAGINATION') : $request->custom_pg_size);
        return $stockList;
    }

    /**
     * Store stock info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return  Object $stock
     */
    public function insert($request)
    {
        for ($i = 0; $i < count($request->product_id); $i++) {
            $stock = new Stock;
            $stock->warehouse_id = $request->warehouse_id[$i];
            $stock->shop_id = $request->shop_id[$i];
            $stock->product_id = $request->product_id[$i];
            $stock->quantity = $request->qty[$i];
            $stock->inout_flg = config('constants.STOCK_IN_FLG');
            $stock->price = $request->price[$i];
            $stock->remark = $request->remark[$i];
            $stock->is_deleted = config('constants.DEL_FLG_OFF');
            $stock->create_user_id = auth()->user()->id;
            $stock->update_user_id = auth()->user()->id;
            $stock->create_datetime = Date('Y-m-d H:i:s');
            $stock->update_datetime = Date('Y-m-d H:i:s');
            $stock->save();

            $warehouseShopProduct = WarehouseShopProductRel::where([
                ['warehouse_id', $request->warehouse_id[$i]],
                ['shop_id', $request->shop_id[$i]],
                ['product_id', $request->product_id[$i]],
            ])->first();
            if (! is_null($warehouseShopProduct)) {
                if ($warehouseShopProduct->price != $request->price[$i]) {
                    $productPriceHistory = new ProductPriceHistory;
                    $productPriceHistory->product_id = $request->product_id[$i];
                    $productPriceHistory->price = $request->price[$i];
                    $productPriceHistory->start_date = Date('Y-m-d H:i:s');
                    $productPriceHistory->is_deleted = config('constants.DEL_FLG_OFF');
                    $productPriceHistory->create_user_id = auth()->user()->id;
                    $productPriceHistory->update_user_id = auth()->user()->id;
                    $productPriceHistory->create_datetime = Date('Y-m-d H:i:s');
                    $productPriceHistory->update_datetime = Date('Y-m-d H:i:s');
                    $productPriceHistory->save();
                }
                WarehouseShopProductRel::where([
                    ['warehouse_id', $request->warehouse_id[$i]],
                    ['shop_id', $request->shop_id[$i]],
                    ['product_id', $request->product_id[$i]],
                ])->update([
                    'quantity' => $warehouseShopProduct->quantity + $request->qty[$i],
                    'price' => $request->price[$i], 'minimum_quantity' => $request->min_qty[$i],
                ]);
            } else {
                $warehouseShopProduct = new WarehouseShopProductRel;
                $warehouseShopProduct->warehouse_id = $request->warehouse_id[$i];
                $warehouseShopProduct->shop_id = $request->shop_id[$i];
                $warehouseShopProduct->product_id = $request->product_id[$i];
                $warehouseShopProduct->quantity = $warehouseShopProduct->quantity + $request->qty[$i];
                $warehouseShopProduct->price = $request->price[$i];
                $warehouseShopProduct->minimum_quantity = $request->min_qty[$i];
                $warehouseShopProduct->save();
            }
        }
        return $stock;
    }

    /**
     * Get low stock info from warehouse shop product table
     *
     * @return Integer
     */
    public function getLowStock()
    {
        $lowStock = WarehouseShopProductRel::whereColumn('quantity', '<=', 'minimum_quantity')->count();
        return $lowStock;
    }

    /**
     * Get warehouse shop product table info
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $warehouseShopProduct
     */
    public function getwarehouseShopProduct($request)
    {
        $warehouseShopProduct = WarehouseShopProductRel::leftJoin('m_product as p', 'p.id', '=', 't_warehouse_shop_product.product_id');
        if ($request->warehouse_id and $request->warehouse_id != 0) {
            $warehouseShopProduct = $warehouseShopProduct->leftJoin('m_warehouse as w', 'w.id', '=', 't_warehouse_shop_product.warehouse_id')
                ->select('p.name as product_name', 'p.product_code as product_code', 'p.id as product_id', 'w.id as warehouse_id', 'w.name as warehouse_name');
        }
        if ($request->shop_id and $request->shop_id != 0) {
            $warehouseShopProduct = $warehouseShopProduct->leftJoin('m_shop as s', 's.id', '=', 't_warehouse_shop_product.shop_id')
                ->select('p.name as product_name', 'p.product_code as product_code', 'p.id as product_id', 's.id as shop_id', 's.name as shop_name');
        }
        $warehouseShopProduct = $warehouseShopProduct->where('t_warehouse_shop_product.product_id', $request->product_id);
        if ($request->shop_id and $request->shop_id != 0) {
            $warehouseShopProduct = $warehouseShopProduct->where('t_warehouse_shop_product.shop_id', $request->shop_id);
        }
        if ($request->warehouse_id and $request->warehouse_id != 0) {
            $warehouseShopProduct = $warehouseShopProduct->where('t_warehouse_shop_product.warehouse_id', $request->warehouse_id);
        }
        $warehouseShopProduct = $warehouseShopProduct->first();
        return $warehouseShopProduct;
    }

    /**
     * Get stock list search by warehouse or product
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getWarehouseStockList($request)
    {
        $warehouseStockList = WarehouseShopProductRel::leftJoin('m_warehouse as w', 'w.id', '=', 't_warehouse_shop_product.warehouse_id')->where('warehouse_id', '!=', null)
            ->where('quantity', '>', 0)->where('product_id', $request->product_id);
        if ($request->warehouse_id) {
            $warehouseStockList = $warehouseStockList->where('warehouse_id', '!=', $request->warehouse_id);
        }
        $warehouseStockList = $warehouseStockList->get();
        return $warehouseStockList;
    }

    /**
     * Get transfering stock to warehouse from warehouse or shop
     *
     * @param \App\Http\Requests\StockTransferRequest $request
     * @return Boolean
     */
    public function stockTransfer($request)
    {
        $stockIn = new Stock;
        $stockIn->warehouse_id = $request->warehouse_id;
        $stockIn->shop_id = $request->shop_id;
        $stockIn->product_id = $request->product_id;
        $stockIn->quantity = $request->qty;
        $stockIn->inout_flg = config('constants.STOCK_IN_FLG');
        $stockIn->price = $request->price;
        $stockIn->is_deleted = config('constants.DEL_FLG_OFF');
        $stockIn->create_user_id = auth()->user()->id;
        $stockIn->update_user_id = auth()->user()->id;
        $stockIn->create_datetime = Date('Y-m-d H:i:s');
        $stockIn->update_datetime = Date('Y-m-d H:i:s');
        $stockIn->save();

        $lastStock = Stock::orderBy('create_datetime', 'desc')->first();

        $stockOut = new Stock;
        $stockOut->warehouse_id = $request->selected_warehouse_id;
        $stockOut->product_id = $request->product_id;
        $stockOut->quantity = $request->qty;
        $stockOut->inout_flg = config('constants.STOCK_OUT_FLG');
        $stockOut->source_location_id = $lastStock->id;
        $stockOut->price = $request->price;
        $stockOut->is_deleted = config('constants.DEL_FLG_OFF');
        $stockOut->create_user_id = auth()->user()->id;
        $stockOut->update_user_id = auth()->user()->id;
        $stockOut->create_datetime = Date('Y-m-d H:i:s');
        $stockOut->update_datetime = Date('Y-m-d H:i:s');
        $stockOut->save();

        $warehouseShopProduct = WarehouseShopProductRel::where([
            ['warehouse_id', $request->warehouse_id],
            ['shop_id', $request->shop_id],
            ['product_id', $request->product_id],
        ])->first();
        WarehouseShopProductRel::where([
            ['warehouse_id', $request->warehouse_id],
            ['shop_id', $request->shop_id],
            ['product_id', $request->product_id],
        ])->update([
            'quantity' => $warehouseShopProduct->quantity + $request->qty,
            'price' => $request->price,
        ]);

        $warehouseProduct = WarehouseShopProductRel::where([
            ['warehouse_id', $request->selected_warehouse_id],
            ['product_id', $request->product_id],
        ])->first();

        WarehouseShopProductRel::where([
            ['warehouse_id', $request->selected_warehouse_id],
            ['product_id', $request->product_id],
        ])->update(['quantity' => $warehouseProduct->quantity - $request->qty]);
        return true;
    }

    /**
     * Get low stock info
     *
     * @return Object $lowStockAlert
     */
    public function lowStockAlert()
    {
        $lowStockAlert = WarehouseShopProductRel::join("m_product", "m_product.id", "=", "t_warehouse_shop_product.product_id")
            ->select("t_warehouse_shop_product.product_id", "t_warehouse_shop_product.quantity", "t_warehouse_shop_product.minimum_quantity", "m_product.name")
            ->whereRaw("t_warehouse_shop_product.quantity <= t_warehouse_shop_product.minimum_quantity")
            ->orderBy("quantity", "DESC")
            ->orderBy("minimum_quantity", "DESC")
            ->limit('1')
            ->first();
        return $lowStockAlert;
    }
}
