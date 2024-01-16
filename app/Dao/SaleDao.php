<?php

namespace App\Dao;

use App\Contracts\Dao\SaleDaoInterface;
use App\Contracts\Dao\StaffDaoInterface;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SalesReturn;
use App\Models\Shop;
use App\Models\Terminal;
use App\Models\WarehouseShopProductRel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Sales Dao
 *
 * @author
 */
class SaleDao implements SaleDaoInterface
{
    private $staffDao;

    /**
     * Class Constructor

     * @param \App\Contracts\Dao\StaffDaoInterface $staffDao
     *
     * @return
     */
    public function __construct(StaffDaoInterface $staffDao)
    {
        $this->staffDao = $staffDao;
    }

    /**
     * Get pending sale invoice count search by terminal id
     *
     * @param  Integer $terminalId
     * @return Integer $salesCount
     */
    public function getPendingSalesCount($terminalId)
    {
        $salesCount = Sale::where('invoice_status', config('constants.INVOICE_PENDING'))->where('terminal_id', $terminalId)->count();
        return $salesCount;
    }

    /**
     * Get sale return exists check by invoice number
     *
     * @param  String $invoiceNo
     * @return Boolean
     */
    public function invoiceExistsInSaleReturn($invoiceNo)
    {
        $invoiceExistsInReturnTable = SalesReturn::where('return_invoice_number', $invoiceNo)
            ->where('is_deleted', config('constants.DEL_FLG_OFF'))
            ->first();
        if ($invoiceExistsInReturnTable) {
            return true;
        }
        return false;
    }

    /**
     * Get sale list
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $saleList
     */
    public function getSaleList($request)
    {
        $saleList = Sale::leftJoin('m_terminal as t', 't_sale.terminal_id', '=', 't.id')
            ->leftJoin('m_shop as shop', 'shop.id', '=', 't.shop_id')
            ->leftJoin('m_staff as stf', 't_sale.create_user_id', '=', 'stf.id');
        $saleList = $saleList->where('t_sale.invoice_status', '!=', config('constants.INVOICE_PENDING'));
        if (! empty($request->search_invoice_no)) {
            $query = $saleList->where('t_sale.invoice_number', 'like', '%' . $request->search_invoice_no . '%');
        }

        // sale date from
        if (! empty($request->search_sale_date_from)) {
            $query = $saleList->where('t_sale.sale_date', '>=', $request->search_sale_date_from);
        }
        // sale date to
        if (! empty($request->search_sale_date_to)) {
            $query = $saleList->where('t_sale.sale_date', '<=', $request->search_sale_date_to);
        }
        // invoice status
        if (! empty($request->search_invoice_status)) {
            $saleList = $saleList->where('t_sale.invoice_status', $request->search_invoice_status);
        }
        // shop name
        if (! empty($request->search_shop_name)) {
            $saleList = $saleList->where('shop.name', 'like', '%' . $request->search_shop_name . '%');
        }

        // weekly, monthly and yearly
        if (empty($request->search_sale_date_from) and empty($request->search_sale_date_to)) {
            if (! empty($request->saledateby)) {
                $dt_min = new \DateTime("last saturday");
                $dt_min->modify('+1 day');
                $dt_max = clone ($dt_min);
                $dt_max->modify('+6 days');
                if ($request->saledateby == 'weekly') {
                    $query = $saleList->where('t_sale.sale_date', '>', $dt_min->format("Y-m-d"));
                    $query = $saleList->where('t_sale.sale_date', '<=', $dt_max->format("Y-m-d"));
                } elseif ($request->saledateby == 'monthly') {
                    $query = $saleList->where('t_sale.sale_date', '>=', $dt_min->format("Y-m-01"));
                    $query = $saleList->where('t_sale.sale_date', '<=', $dt_max->format("Y-m-t"));
                } elseif ($request->saledateby == 'yearly') {
                    $query = $saleList->where('t_sale.sale_date', '>=', $dt_min->format("Y-01-01"));
                    $query = $saleList->where('t_sale.sale_date', '<=', $dt_max->format("Y-12-t"));
                } else {
                    $query = $saleList->where('t_sale.sale_date', '=', date("Y-m-d"));
                }
            }
        }
        // sorting list order
        if ($request->sorting_column) {
            $saleList = $saleList->orderBy($request->sorting_column, $request->sorting_order);
        }

        $saleList = $saleList->select(DB::raw('t_sale.id,t_sale.invoice_status,t_sale.sale_date,t_sale.invoice_number,shop.name as shop_name,t.name as terminal_name, t_sale.amount, t_sale.amount, t_sale.amount_tax, stf.name as staff_name, t_sale.remark as remark, (t_sale.amount+t_sale.amount_tax)as total'));
        $saleList = $saleList->paginate($request->custom_pg_size == "" ? config('constants.SALE_PAGINATION') : $request->custom_pg_size);
        return $saleList;
    }

    /**
     * Get total sale quantity search by sale id
     *
     * @param  Integer $sale_id
     * @return Object
     */
    public function getTotalSaleQtyBySaleId($sale_id)
    {
        $query = Sale::leftJoin('t_sale_details as detail', 't_sale.id', '=', 'detail.sale_id')
            ->where('detail.sale_id', '=', $sale_id)
            ->groupBy('detail.sale_id')
            ->select(DB::raw('detail.sale_id,SUM(detail.quantity) as total_sale_qty'))
            ->get();
        if (! empty($query)) {
            $retValue = $query->pluck('total_sale_qty')->first();
            return $retValue;
        }
    }

    /**
     * Get sale lists that have been confirmed invoice
     *
     * @param  \App\Http\Requests\SaleReturnInvoiceListRequest $request
     * @return Object $saleList
     */
    public function getConfirmedSaleList($request)
    {
        $saleList = Sale::leftJoin('m_terminal as t', 't_sale.terminal_id', '=', 't.id')
            ->leftJoin('m_shop as shop', 'shop.id', '=', 't.shop_id')
            ->leftJoin('m_staff as stf', 't_sale.create_user_id', '=', 'stf.id')
            ->where('t_sale.invoice_status', '=', config('constants.INVOICE_CONFIRM'));

        // invoice number
        if (! empty($request->search_invoice_no)) {
            $saleList = $saleList->where('t_sale.invoice_number', 'like', '%' . $request->search_invoice_no . '%');
        }
        // sale date from
        if (! empty($request->search_sale_date_from)) {
            $saleList = $saleList->where('t_sale.sale_date', '>=', $request->search_sale_date_from);
        }
        // sale date to
        if (! empty($request->search_sale_date_to)) {
            $saleList = $saleList->where('t_sale.sale_date', '<=', $request->search_sale_date_to);
        }
        // staff name
        if (! empty($request->search_staff_name)) {
            $saleList = $saleList->where('stf.name', 'like', '%' . $request->search_staff_name . '%');
        }

        $saleList = $saleList->select(DB::raw('t_sale.id,t_sale.invoice_status,t_sale.sale_date,t_sale.invoice_number, t.name as terminal_name, t_sale.amount, t_sale.amount, t_sale.amount_tax, stf.name as staff_name, t_sale.remark as remark, (t_sale.amount+t_sale.amount_tax)as total,shop.name as shop_name'));
        $saleList = $saleList->paginate($request->custom_pg_size == "" ? config('constants.SALE_PAGINATION') : $request->custom_pg_size);

        return $saleList;
    }

    /**
     * Get sale info search by id
     *
     * @param  Integer $sale_id
     * @return Object $saleInfo
     */
    public function getSaleInfoById($sale_id)
    {
        $saleInfo = Sale::leftJoin('m_terminal as t', 't_sale.terminal_id', '=', 't.id')
            ->leftJoin('m_staff as stf', 't_sale.create_user_id', '=', 'stf.id')
            ->leftJoin('m_shop as shop', 't.shop_id', '=', 'shop.id');
        if (! empty($sale_id)) {
            $saleInfo = $saleInfo->where('t_sale.id', '=', $sale_id);
        }

        $saleInfo = $saleInfo->select(DB::raw('shop.name as shop_name,t_sale.invoice_number, t_sale.sale_date, stf.name as staff_name, t.name as terminal_name, t_sale.amount, t_sale.amount, (t_sale.amount+t_sale.amount_tax)as total'))->get();
        //$saleInfo->header = ["Invoice Number", "Sale Date", "Staff Name", "Terminal Name", "Amount", "Total"];
        return $saleInfo;
    }

    /**
     * Update product quantity (warehouse shop product table) in storage by adding quantity
     *
     * @param  Integer $shop_id
     * @param  Object $request
     * @return Object $warehouseShopProduct
     */
    public function updateQtyPlus($shop_id, $request)
    {
        $warehouseShopProduct = WarehouseShopProductRel::where([
            ['shop_id', $shop_id],
            ['product_id', $request->product_id],
        ])->first();
        if (! is_null($warehouseShopProduct)) {
            WarehouseShopProductRel::where([
                ['shop_id', $shop_id],
                ['product_id', $request->product_id],
            ])->update([
                'quantity' => $warehouseShopProduct->quantity + $request->qty,
            ]);
        }
        return $warehouseShopProduct;
    }

    /**
     * Update product quantity (warehouse shop product table) in storage by reducing quantity
     *
     * @param Object $request
     * @return Object $warehouseShopProduct
     */
    public function updateQtyMinus($request)
    {
        $warehouseShopProduct = WarehouseShopProductRel::where([
            ['shop_id', $request->shop_id],
            ['product_id', $request->product_id],
        ])->first();
        if (! is_null($warehouseShopProduct)) {
            WarehouseShopProductRel::where([

                ['shop_id', $request->shop_id],
                ['product_id', $request->product_id],
            ])->update([
                'quantity' => $warehouseShopProduct->quantity - $request->qty,

            ]);
        }
        return $warehouseShopProduct;
    }

    /**
     * Cancel Sale Invoice
     *
     * @param  Object $request
     * @return Boolean
     */
    public function cancelSaleInvoice($request)
    {
        // update sale
        $sale = Sale::where('id', $request->id)
            ->update([
                'invoice_status' => config('constants.INVOICE_CANCELLED'),
                'reason' => $request->cancellation_reason,
                'update_user_id' => auth()->user()->id,
                'update_datetime' => Date('Y-m-d H:i:s'),
            ]);
        // get shop resource
        $shop = Sale::select('m_shop.id', 'm_shop.name')
            ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->where('t_sale.id', $request->id)
            ->first();
        // get sale details list and loop to update product quantity by adding
        $saleDetails = SaleDetail::where('sale_id', '=', $request->id)->get();
        foreach ($saleDetails as $key => $value) {
            \DB::table('t_warehouse_shop_product')->where('product_id', $value->product_id)
                ->where('shop_id', $shop->id)
                ->increment('quantity', $value->quantity);
        }
        return $sale;
    }

    /**
     * Get total sale amount for today
     *
     * @return Object or boolean
     */
    public function getTodaySaleTotal()
    {
        $query = Sale::select(DB::raw("sum(amount)"), "sale_date")
            ->where('sale_date', '=', Date("Y-m-d"))
            ->where('invoice_status', '=', config('constants.INVOICE_CONFIRM'))
            ->groupBy('sale_date')
            ->first();
        if (! empty($query)) {
            return $query;
        }
        return false;
    }

    /**
     * Get total sale amount for yesterday
     *
     * @return Object or boolean
     */
    public function getYesterdaySaleTotal()
    {
        $query = Sale::select(DB::raw("sum(amount)"), "sale_date")
            ->where('sale_date', '=', Date("Y-m-d", strtotime('-1 day')))
            ->where('invoice_status', '=', config('constants.INVOICE_CONFIRM'))
            ->groupBy('sale_date')
            ->first();
        if (! empty($query)) {
            return $query;
        }
        return false;
    }

    /**
     * Get total sale amount for current month
     *
     * @return Object or boolean
     */
    public function getMonthSaleTotal()
    {
        $query = Sale::select(DB::raw("sum(amount)"))
            ->where('sale_date', '>=', Date("Y-m-01"))
            ->where('sale_date', '<=', Date("Y-m-t"))
            ->where('invoice_status', '=', config('constants.INVOICE_CONFIRM'))
            ->first();
        if (! empty($query)) {
            return $query;
        }
        return false;
    }

    /**
     * Get total sale amount for current year
     *
     * @return Object or boolean
     */
    public function getYearSaleTotal()
    {
        $query = Sale::select(DB::raw("sum(amount)"))
            ->where('sale_date', '>=', Date("Y-01-01"))
            ->where('sale_date', '<=', Date("Y-12-t"))
            ->where('invoice_status', '=', config('constants.INVOICE_CONFIRM'))
            ->first();
        if (! empty($query)) {
            return $query;
        }
        return false;
    }

    /**
     * Get sale date, total sale amount and shop name for today
     *
     * @return Object or boolean
     */
    public function getTodaySaleShops()
    {
        $query = Sale::select(DB::raw("sum(t_sale.amount)"), "t_sale.sale_date", "m_shop.name")
            ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->where('t_sale.sale_date', '=', Date("Y-m-d"))
            ->groupBy('t_sale.sale_date', "m_shop.name")
            ->get();
        if (! empty($query)) {
            return $query->toArray();
        }
        return false;
    }

    /**
     * Get sale list search by sale dates
     *
     * @param Array $sale_dates
     * @return Array or boolean
     */
    public function getSaleDataByDates($sale_dates)
    {
        $query = Sale::select(\DB::raw("sum(amount)"), 'sale_date')
            ->whereIn("sale_date", $sale_dates)
            ->where('invoice_status', '=', config('constants.INVOICE_CONFIRM'))
            ->groupBy("sale_date")
            ->get();
        if (! empty($query)) {
            return $query->toArray();
        }
        return false;
    }

    /**
     * Get monthly sale info search by sale from date and sale to date
     *
     * @param Date $from_date
     * @param Date $to_date
     * @return Array or boolean
     */
    public function getMonthlySale($from_date, $to_date)
    {
        $query = Sale::select(DB::raw("sum(amount)"), DB::raw("date_part('month', sale_date) AS Sale_Month"))
            ->where('invoice_status', '=', config('constants.INVOICE_CONFIRM'))
            ->whereDate("sale_date", ">=", $from_date)
            ->whereDate("sale_date", "<=", $to_date)
            ->groupBy(DB::raw("date_part('month', sale_date)"))
            ->get();
        if (! empty($query)) {
            return $query->toArray();
        }
        return false;
    }

    /**
     * Get all total sales amount
     *
     * @return Integer or boolean
     */
    public function getSaleTotal()
    {
        $query = Sale::select(DB::raw("sum(amount)"))
            ->where('invoice_status', '=', config('constants.INVOICE_CONFIRM'))
            ->first();
        if (! empty($query)) {
            return $query;
        }
        return false;
    }

    /**
     * Get sale list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleList
     */
    public function getSaleListForExport($request)
    {
        $saleList = Sale::leftJoin('m_terminal as t', 't_sale.terminal_id', '=', 't.id')
            ->leftJoin('m_shop as shop', 'shop.id', '=', 't.shop_id')
            ->leftJoin('m_staff as stf', 't_sale.create_user_id', '=', 'stf.id');
        $saleList = $saleList->where('t_sale.invoice_status', '!=', config('constants.INVOICE_PENDING'));
        if (! empty($request->search_invoice_no)) {
            $query = $saleList->where('t_sale.invoice_number', 'like', '%' . $request->search_invoice_no . '%');
        }

        // from date
        if (! empty($request->search_sale_date_from)) {
            $query = $saleList->where('t_sale.sale_date', '>=', $request->search_sale_date_from);
        }
        // to date
        if (! empty($request->search_sale_date_to)) {
            $query = $saleList->where('t_sale.sale_date', '<', $request->search_sale_date_to);
        }
        // invoice status
        if (! empty($request->search_invoice_status)) {
            $saleList = $saleList->where('t_sale.invoice_status', $request->search_invoice_status);
        }
        // shop name
        if (! empty($request->search_shop_name)) {
            $saleList = $saleList->where('shop.name', 'like', '%' . $request->search_shop_name . '%');
        }
        // weekly, monthly, yearly
        if (empty($request->search_sale_date_from) and empty($request->search_sale_date_to)) {
            if (! empty($request->saledateby)) {
                $dt_min = new \DateTime("last saturday"); // Edit
                $dt_min->modify('+1 day'); // Edit
                $dt_max = clone ($dt_min);
                $dt_max->modify('+6 days');
                if ($request->saledateby == 'weekly') {
                    $query = $saleList->where('t_sale.sale_date', '>', $dt_min->format("Y-m-d"));
                    $query = $saleList->where('t_sale.sale_date', '<=', $dt_max->format("Y-m-d"));
                } elseif ($request->saledateby == 'monthly') {
                    $query = $saleList->where('t_sale.sale_date', '>=', $dt_min->format("Y-m-01"));
                    $query = $saleList->where('t_sale.sale_date', '<=', $dt_max->format("Y-m-t"));
                } elseif ($request->saledateby == 'yearly') {
                    $query = $saleList->where('t_sale.sale_date', '>=', $dt_min->format("Y-01-01"));
                    $query = $saleList->where('t_sale.sale_date', '<=', $dt_max->format("Y-12-t"));
                } else {
                    $query = $saleList->where('t_sale.sale_date', '=', date("Y-m-d"));
                }
            }
        }

        // sorting sale list order
        if ($request->sorting_column) {
            $saleList = $saleList->orderBy($request->sorting_column, $request->sorting_order);
        }

        $saleList = $saleList->select(DB::raw('t_sale.sale_date,t_sale.invoice_number,shop.name as shop_name,t.name as terminal_name, t_sale.amount, t_sale.amount, t_sale.amount_tax, stf.name as staff_name, t_sale.remark as remark, (t_sale.amount+t_sale.amount_tax)as total'));
        $saleList = $saleList->get();
        return $saleList;
    }

    /**
     * Get sale category list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleList
     */
    public function getSaleCategoryListForExport($request)
    {
        $salecategory = SaleDetail::join("t_sale", "t_sale.id", "=", "t_sale_details.sale_id")
            ->join("m_product as product", "product.id", "=", "t_sale_details.product_id")
            ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->join("m_category", "m_category.id", "=", "product.product_type_id")
            ->select(\DB::raw("m_shop.name as shop_name,m_category.name as category_name, t_sale.sale_date as date"))
            ->where(function ($q) use ($request) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }

                if (! empty($request->from_date) && ! empty($request->to_date)) {
                    $q->whereBetween('t_sale.sale_date', [$request->from_date, $request->to_date]);
                }
            })
            ->get();
        return $salecategory;
    }

    /**
     * Get sale product list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $salePorductList
     */
    public function getSaleProductListForExport($request)
    {
        $saleproduct = SaleDetail::join("t_sale", "t_sale.id", "=", "t_sale_details.sale_id")
            ->join("m_product as product", "product.id", "=", "t_sale_details.product_id")
            ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->select(\DB::raw("m_shop.name as shop_name, product.name as product_name, t_sale_details.quantity as quantity, t_sale.sale_date as date"))
            ->where(function ($q) use ($request) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }

                if (! empty($request->from_date) && ! empty($request->to_date)) {
                    $q->whereBetween('t_sale.sale_date', [$request->from_date, $request->to_date]);
                }
            })
            ->get();
        return $saleproduct;
    }

    /**
     * Get sale product list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $salePorductList
     */
    public function getTopSaleProductListForExport($request)
    {
        $topsaleproduct = SaleDetail::join("t_sale", "t_sale.id", "=", "t_sale_details.sale_id")
            ->join("m_product", "m_product.id", "=", "t_sale_details.product_id")
            ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->select(\DB::raw("m_shop.name as shop_name, m_product.name as product_name, sum(t_sale_details.quantity) as total_quantity"))
            ->where(function ($q) use ($request) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }

                if (! empty($request->from_date) && ! empty($request->to_date)) {
                    $q->whereBetween('t_sale.sale_date', [$request->from_date, $request->to_date]);
                }
            })
            ->groupBy("m_product.id", "shop_name")
            ->orderBy("total_quantity", "DESC")
            ->limit(10)
            ->get();
        return $topsaleproduct;
    }

    /**
     * Get invoice list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $invoiceList
     */
    public function getInvoiceDataExport($request)
    {
        $sale = Sale::join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->select(\DB::raw("m_shop.name as shop_name,t_sale.amount,t_sale.amount_tax,t_sale.sale_date,t_sale.invoice_number"))
            ->where(function ($q) use ($request) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }
                if (! empty($request->from_date)) {
                    $q->whereDate('t_sale.sale_date', '>=', $request->from_date);
                }

                if (! empty($request->to_date)) {
                    $q->whereDate('t_sale.sale_date', '<=', $request->to_date);
                }
            })
            ->where('t_sale.invoice_status', config('constants.INVOICE_CONFIRM'))
            ->groupBy("t_sale.id", "m_shop.name")->get();
        $sale->header = ["Shop Name", "Invoice Number", "Sale Date", "Sale Amount", "Sale Tax Amount"];
        return $sale;
    }

    /**
     * Get invoice detail list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $invoiceDetailList
     */
    public function getInvoiceDetailDataExport($request)
    {
        $sale = SaleDetail::join("t_sale", "t_sale.id", "=", "t_sale_details.sale_id")
            ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->join("m_product", "m_product.id", "=", "t_sale_details.product_id")
            ->select(\DB::raw("m_shop.name as shop_name,t_sale.invoice_number,t_sale_details.price,t_sale_details.quantity,t_sale.sale_date,m_product.name as product_name"))
            ->where(function ($q) use ($request) {
                if (! empty($request->invoice_number)) {
                    $q->where('t_sale.invoice_number', '=', $request->invoice_number);
                } else {
                    $q->where('t_sale.invoice_number', '=', "0");
                }
            })
            ->where('t_sale.invoice_status', config('constants.INVOICE_CONFIRM'))
            ->groupBy("t_sale.id", "m_shop.name", "m_product.name", "t_sale_details.price", "t_sale_details.quantity")
            ->get();
        $sale->header = ["Shop Name", "Invoice Number", "Product Name", "Sale Price", "Sale Quantity", "Sale Date"];
        return $sale;
    }

    /**
     * Get best selling product info
     *
     * @return Object $product
     */
    public function bestSellingProduct()
    {
        $product = Sale::join("t_sale_details as sd", "sd.sale_id", "=", "t_sale.id")
            ->join("m_product as product", "product.id", "=", "sd.product_id")
            ->groupBy("sd.product_id", "product.name")
            ->select("sd.product_id", "product.name", DB::raw("sum(sd.quantity)"))
            ->orderBy("sum", "DESC")
            ->limit('1')
            ->first();
        return $product;
    }

    /**
     * Insert sale info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @return Integer $sale_id
     */
    public function insertSale($request)
    {
        $shopid = str_pad(Auth::user()->shop_id, 3, '0', STR_PAD_LEFT);
        $terminal_id = str_pad($request->terminal_id, 3, '0', STR_PAD_LEFT);

        $lastvalue = DB::select("select last_value from t_sale_id_seq");
        $lastsaleid = $lastvalue[0]->last_value;
        // $lastsale = Sale::select('id')->orderBy('id', 'DESC')->first();
        // if ($lastsale) {
        //     $lastsaleid = Sale::select('id')->orderBy('id', 'DESC')->first()->id;
        // } else {
        //     $lastsaleid = 0;
        // }
        $lastsaleid = str_pad($lastsaleid+1, 4, '0', STR_PAD_LEFT);
        $invoice_number = $shopid . $terminal_id . date("ymd") . $lastsaleid;

        $sale = new Sale;
        $sale->invoice_number = $invoice_number;
        $sale->terminal_id = $request->terminal_id;
        $sale->shop_id = Auth()->user()->shop_id;
        $sale->sale_date = date('Y-m-d');
        $sale->invoice_status = config('constants.INVOICE_PENDING');
        $sale->create_user_id = Auth::user()->id;
        $sale->update_user_id = Auth::user()->id;
        $sale->create_datetime = date('Y-m-d H:i:s');
        $sale->update_datetime = date('Y-m-d H:i:s');
        $sale->amount = 0;
        $sale->amount_tax = 0;
        $sale->paid_amount = 0;
        $sale->change_amount = 0;
        $sale->save();

        return $sale->id;
    }

    /**
     * Check sale exist by sale id
     *
     * @param  Integer $sale_id
     * @return Object $sale
     */
    public function checkSaleIDExists($sale_id)
    {
        $sale = Sale::FindOrFail($sale_id);
        return $sale;
    }

    /**
     * Invoice sale by sale staff
     *
     * @return Boolean
     */
    public function invoiceSale($request)
    {
        $invoiceDetails = Sale::where('id', $request->id)
            ->update([
                'invoice_status' => config('constants.INVOICE_CONFIRM'),
                // 'amount' => $request->amount,
                // 'amount_tax' => $request->amount_tax,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->change_amount,
            ]);
        if ($invoiceDetails) {
            $saleItemDetails = SaleDetail::where('sale_id', $request->id)->get();
            // decrease sale product quantity in Warehouse_Shop_Product table
            $stockLowQuantity = 0;
            foreach ($saleItemDetails as $value) {
                WarehouseShopProductRel::where([
                    'shop_id' => Auth::user()->shop_id,
                    'product_id' => $value->product_id,
                ])->decrement('quantity', $value->quantity);

                $checkLowStockQuantity = WarehouseShopProductRel::
                    whereColumn('quantity', '<=', 'minimum_quantity')
                        ->where([
                            'shop_id' => Auth::user()->shop_id,
                            'product_id' => $value->product_id,
                        ])->first();
                if ($checkLowStockQuantity) {
                    $stockLowQuantity += 1;
                }
            }
            if ($stockLowQuantity > 0) {
                $messageInfo = [
                    'body' => 'Low Stock Quantity!',
                    'type' => config('constants.NOTIFICATION_LOW_STOCK'),
                ];
                $staffList = $this->staffDao->getStaffListByShopIDArray([Auth::user()->shop_id]);
                foreach ($staffList as $key => $value) {
                    $staff = \App\Models\Staff::find($value->id);
                    $staff->notify(new \App\Notifications\AdminNotification($messageInfo));
                }
            }

            return $invoiceDetails;
        }
    }

    /**
     * Get Sale Invoice Recent List from storage
     *
     * @return Object SaleList
     */
    public function getInvoiceRecentList($request)
    {
        $saleList = Sale::where('invoice_status', config('constants.INVOICE_CONFIRM'))
            ->orderBy('id', 'DESC')
            ->where(function ($q) use ($request) {
                if (! empty($request->invoice_number)) {
                    $q->where("invoice_number", "like", "%" . $request->invoice_number . "%");
                }
            })
            ->orderBy('update_datetime', 'DESC')
            ->limit(10)
            ->get();
        return $saleList;
    }

    /**
     * Get Sale Invoice Pending List from storage
     *
     * @return Object SaleList
     */
    public function getInvoicePendingList($request)
    {
        $saleList = Sale::where('invoice_status', config('constants.INVOICE_PENDING'))
            ->orderBy('id', 'DESC')
            ->where(function ($q) use ($request) {
                if (! empty($request->invoice_number)) {
                    $q->where("invoice_number", "like", "%" . $request->invoice_number . "%");
                }
            })
            ->orderBy('update_datetime', 'DESC')
            ->limit(10)
            ->get();
        return $saleList;
    }

    /**
     * Get Sale Invoice Details from table
     *
     * @return Object $invoiceDetails
     */
    public function getSaleInvoiceDetails($request)
    {
        $invoiceDetails = Sale::where('invoice_number', $request->invoice_number)
        // ->where('invoice_status', config('constants.INVOICE_CONFIRM'))
        // ->orWhere('invoice_status', config('constants.INVOICE_PENDING'))
            ->first();
        return $invoiceDetails;
    }

    /**
     * Confirm Sale Invoice Details
     *
     * @param  App\Http\Requests\Api\SaleInvoiceDetailsRequest $request
     * @return Object
     */
    public function confirmSaleInvoiceDetails($request)
    {
        $invoiceDetails = Sale::where('invoice_number', $request->invoice_number)
            ->first();
        if (! $invoiceDetails) {
            return false;
        }
        if ($invoiceDetails->invoice_status == config('constants.INVOICE_PENDING')) {
            $totalAmount = 0;
            $taxAmount = 0;
            foreach ($invoiceDetails->details as $key => $value) {
                $totalAmount += $value->quantity * $value->price;
                # code...
            }
            $taxAmount = $totalAmount * 5 / 100;
            $invoiceDetails->amount = $totalAmount;
            $invoiceDetails->amount_tax = $taxAmount;
            $invoiceDetails->save();
            return $invoiceDetails;
        }
        return $invoiceDetails;
    }
}
