<?php

namespace App\Dao;

use App\Contracts\Dao\ReportDaoInterface;
use App\Models\Company;
use App\Models\CompanyLicense;
use App\Models\DamageDetail;
use App\Models\Product;
use App\Models\ReturnDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\ShopProduct;
use App\Models\WarehouseShopProductRel;
use Illuminate\Support\Facades\DB;

/**
 * Report Dao
 *
 * @author
 */
class ReportDao implements ReportDaoInterface
{

    /**
     * Get report data from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getData($request)
    {
        if (! empty($request->select_report)) {
            /******************** best_selling_report *******************/
            if ($request->select_report == 'best_selling_report') {
                $search_shop_name = $request->search_shop_name;
                $product = \App\Models\Sale::join("t_sale_details as sd", "sd.sale_id", "=", "t_sale.id")
                    ->join("m_product as product", "product.id", "=", "sd.product_id")
                    ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
                    ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
                    ->select(\DB::raw("m_shop.name as shop_name,m_shop.id"))
                    ->where(function ($q) use ($search_shop_name) {
                        if (! empty($search_shop_name)) {
                            $q->where("m_shop.id", "=", $search_shop_name);
                        }
                    })
                    ->groupBy("m_shop.name", "m_shop.id")
                    ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
                $product->header = ["product code", "product name", "shop name", "total quantity"];
                foreach ($product as $item) {
                    $tmp = \App\Models\Shop::join("m_terminal", "m_shop.id", "=", "m_terminal.shop_id")
                        ->join("t_sale", "m_terminal.id", "=", "t_sale.terminal_id")
                        ->join("t_sale_details", "t_sale_details.sale_id", "=", "t_sale.id")
                        ->join("m_product", "m_product.id", "=", "t_sale_details.product_id")
                        ->select(\DB::raw("sum(t_sale_details.quantity)"), "t_sale_details.product_id", "m_product.name", "m_product.product_code")
                        ->where("m_shop.id", "=", $item->id)
                        ->groupBy("t_sale_details.product_id", "m_product.name", "m_product.product_code")
                        ->get()->toArray();

                    $key = array_search(max(array_column($tmp, 'sum')), array_column($tmp, 'sum'));
                    $item->name = $tmp[$key]['name'];
                    $item->total_sum = $tmp[$key]['sum'];
                    $item->product_code = $tmp[$key]['product_code'];
                }
                return $product;
            }
            /******************** sales_by_product_category_report *******************/
            if ($request->select_report == 'sales_by_product_category_report') {
                $search_category_name = $request->search_category_name;
                $sale = \App\Models\SaleDetail::join("t_sale", "t_sale.id", "=", "t_sale_details.sale_id")
                    ->join("m_product as product", "product.id", "=", "t_sale_details.product_id")
                    ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
                    ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
                    ->join("m_category", "m_category.id", "=", "product.product_type_id")
                    ->select(\DB::raw("m_shop.name as shop_name,m_category.name category_name,sum(t_sale_details.price * t_sale_details.quantity) as total"))
                    ->where(function ($q) use ($search_category_name) {
                        if (! empty($search_category_name)) {
                            $q->where("m_category.id", "=", $search_category_name);
                        }
                    })
                    ->groupBy("shop_name", "category_name")
                    ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
                $sale->header = ["Shop Name", "Category Name", "Total Amount"];
                return $sale;
            }
            /******************** damage_and_loss_report *******************/
            if ($request->select_report == 'damage_and_loss_report') {
                $search_warehouse_name = $request->search_warehouse_name;
                $search_shop_name = $request->search_shop_name;

                $damage_loss = \App\Models\DamageDetail::join(
                    "t_damage_loss",
                    "t_damage_loss.id",
                    "=",
                    "t_damage_loss_details.damage_loss_id"
                )
                    ->leftJoin("m_warehouse", "m_warehouse.id", "t_damage_loss.warehouse_id")
                    ->leftJoin("m_shop", "m_shop.id", "t_damage_loss.shop_id")
                    ->join("m_product", "m_product.id", "=", "t_damage_loss_details.product_id")
                    ->select(\DB::raw('t_damage_loss.warehouse_id,m_warehouse.name as warehouse_name,m_shop.name as shop_name,t_damage_loss.shop_id,sum(t_damage_loss_details.quantity) as total_damage_qty,t_damage_loss_details.product_id,m_product.name as product_name'))
                    ->where(function ($q) use ($search_warehouse_name, $search_shop_name) {
                        if (! empty($search_warehouse_name) and ! empty($search_shop_name)) {
                            $q->where("t_damage_loss.warehouse_id", "=", $search_warehouse_name);
                            $q->orwhere("t_damage_loss.shop_id", "=", $search_shop_name);
                        } else {
                            if (! empty($search_warehouse_name)) {
                                $q->where("t_damage_loss.warehouse_id", "=", $search_warehouse_name);
                            }
                            if (! empty($search_shop_name)) {
                                $q->where("t_damage_loss.shop_id", "=", $search_shop_name);
                            }
                        }
                    })
                    ->groupBy("t_damage_loss_details.product_id", "t_damage_loss.warehouse_id", "t_damage_loss.shop_id", "warehouse_name", "shop_name", "product_name")
                    ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
                $damage_loss->header = ["shop name", "warehouse name", "product name", "total damage quantity"];
                return $damage_loss;
            }
            /******************** others report *******************/
        }
        return [];
    }
    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getDataExport($request)
    {
        if (! empty($request->select_report)) {
            /******************** best_selling_report *******************/
            if ($request->select_report == 'best_selling_report') {
                $search_shop_name = $request->search_shop_name;
                $product = \App\Models\Sale::join("t_sale_details as sd", "sd.sale_id", "=", "t_sale.id")
                    ->join("m_product as product", "product.id", "=", "sd.product_id")
                    ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
                    ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
                    ->select(\DB::raw("m_shop.name as shop_name,m_shop.id"))
                    ->where(function ($q) use ($search_shop_name) {
                        if (! empty($search_shop_name)) {
                            $q->where("m_shop.id", "=", $search_shop_name);
                        }
                    })
                    ->groupBy("m_shop.name", "m_shop.id")
                    ->get('shop_name', ['m_shop.id']);
                foreach ($product as $item) {
                    $tmp = \App\Models\Shop::join("m_terminal", "m_shop.id", "=", "m_terminal.shop_id")
                        ->join("t_sale", "m_terminal.id", "=", "t_sale.terminal_id")
                        ->join("t_sale_details", "t_sale_details.sale_id", "=", "t_sale.id")
                        ->join("m_product", "m_product.id", "=", "t_sale_details.product_id")
                        ->select(\DB::raw("sum(t_sale_details.quantity)"), "t_sale_details.product_id", "m_product.name", "m_product.product_code")
                        ->where("m_shop.id", "=", $item->id)
                        ->groupBy("t_sale_details.product_id", "m_product.name", "m_product.product_code")
                        ->get()->toArray();

                    $key = array_search(max(array_column($tmp, 'sum')), array_column($tmp, 'sum'));
                    $item->name = $tmp[$key]['name'];
                    $item->total_sum = $tmp[$key]['sum'];
                    $item->product_code = $tmp[$key]['product_code'];
                }
                return $product;
            }

            /******************** sales_by_product_category_report *******************/
            if ($request->select_report == 'sales_by_product_category_report') {
                $search_category_name = $request->search_category_name;
                $sale = \App\Models\SaleDetail::join("t_sale", "t_sale.id", "=", "t_sale_details.sale_id")
                    ->join("m_product as product", "product.id", "=", "t_sale_details.product_id")
                    ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
                    ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
                    ->join("m_category", "m_category.id", "=", "product.product_type_id")
                    ->select(\DB::raw("m_shop.name as shop_name,m_category.name category_name,sum(t_sale_details.price * t_sale_details.quantity) as total"))
                    ->where(function ($q) use ($search_category_name) {
                        if (! empty($search_category_name)) {
                            $q->where("m_category.id", "=", $search_category_name);
                        }
                    })
                    ->groupBy("shop_name", "category_name")
                    ->get();
                return $sale;
            }
            /******************** damage_and_loss_report *******************/
            if ($request->select_report == 'damage_and_loss_report') {
                $search_warehouse_name = $request->search_warehouse_name;
                $search_shop_name = $request->search_shop_name;

                $damage_loss = \App\Models\DamageDetail::join(
                    "t_damage_loss",
                    "t_damage_loss.id",
                    "=",
                    "t_damage_loss_details.damage_loss_id"
                )
                    ->leftJoin("m_warehouse", "m_warehouse.id", "t_damage_loss.warehouse_id")
                    ->leftJoin("m_shop", "m_shop.id", "t_damage_loss.shop_id")
                    ->join("m_product", "m_product.id", "=", "t_damage_loss_details.product_id")
                    ->select(\DB::raw('m_shop.name as shop_name,m_warehouse.name as warehouse_name,m_product.name as product_name,sum(t_damage_loss_details.quantity) as total_damage_qty'))
                    ->where(function ($q) use ($search_warehouse_name, $search_shop_name) {
                        if (! empty($search_warehouse_name) and ! empty($search_shop_name)) {
                            $q->where("t_damage_loss.warehouse_id", "=", $search_warehouse_name);
                            $q->orwhere("t_damage_loss.shop_id", "=", $search_shop_name);
                        } else {
                            if (! empty($search_warehouse_name)) {
                                $q->where("t_damage_loss.warehouse_id", "=", $search_warehouse_name);
                            }
                            if (! empty($search_shop_name)) {
                                $q->where("t_damage_loss.shop_id", "=", $search_shop_name);
                            }
                        }
                    })
                    ->groupBy("t_damage_loss_details.product_id", "t_damage_loss.warehouse_id", "t_damage_loss.shop_id", "warehouse_name", "shop_name", "product_name")
                    ->get();
                return $damage_loss;
            }
            /******************** others report *******************/
        }
    }

    /**
     * Get sale report data from storage
     *
     * @param \Illuminate\Http\SaleReportRequest $request
     * @return Object or array
     */
    public function getSaleReportData($request, $date)
    {
        $sale = Sale::join("m_shop", "m_shop.id", "=", "t_sale.shop_id")
            ->select(\DB::raw("m_shop.name as shop_name,t_sale.sale_date,sum(t_sale.amount) as totalamount"))
            ->where(function ($q) use ($request, $date) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }
                if (! empty($date)) {
                    $q->where('t_sale.sale_date', '=', $date);
                }
            })
            ->where('t_sale.invoice_status', config('constants.INVOICE_CONFIRM'))
            ->groupBy("m_shop.name", "t_sale.sale_date")
            ->first();
        return $sale;
    }

    /**
     * Get sale report data from storage
     *
     * @param \Illuminate\Http\SaleCategoryReportRequest $request
     * @return Object or array
     */
    public function getSaleCategoryReportData($request)
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
            ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
        return $salecategory;
    }

    /**
     * Get sale report data from storage
     *
     * @param \Illuminate\Http\SaleCategoryReportRequest $request
     * @return Object or array
     */
    public function getSaleProductReportData($request)
    {
        $saleproduct = SaleDetail::join("t_sale", "t_sale.id", "=", "t_sale_details.sale_id")
            ->join("m_product as product", "product.id", "=", "t_sale_details.product_id")
            ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->select(\DB::raw("m_shop.name as shop_name, product.name as product_name, t_sale.sale_date as date, t_sale_details.quantity as quantity"))
            ->where(function ($q) use ($request) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }

                if (! empty($request->from_date) && ! empty($request->to_date)) {
                    $q->whereBetween('t_sale.sale_date', [$request->from_date, $request->to_date]);
                }
            })
            ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
        return $saleproduct;
    }

    /**
     * Get top sale report data from storage
     *
     * @param \Illuminate\Http\SaleCategoryReportRequest $request
     * @return Object or array
     */
    public function getTopSaleProductReportData($request)
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
     * Get product report data from storage
     *
     * @param \Illuminate\Http\ProductReportRequest $request
     * @return Object or array
     */
    public function getProductReportData($request)
    {
        $product = ShopProduct::join("m_shop as s", "s.id", "=", "m_shop_product.shop_id")
            ->leftJoin("m_product as p", "p.id", "=", "m_shop_product.product_id")
            ->leftJoin("t_warehouse_shop_product as wsp", "wsp.product_id", "=", "m_shop_product.product_id")
            ->select('s.name as shop_name', 'p.name as product_name', 'wsp.quantity as stock_quantity')
            ->where(function ($q) use ($request) {
                if (! empty($request->select_shop_name)) {
                    $q->where("m_shop_product.shop_id", "=", $request->select_shop_name);
                }
            })
            ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
        return $product;
    }

    /**
     * Get company license report data from storage
     *
     * @param \Illuminate\Http\CompanyLicenseReportRequest $request
     * @return Object or array
     */
    public function getCompanyLicenseReportData($request)
    {
        $companylicense = CompanyLicense::join("m_company", "m_company.id", "=", "m_company_license.company_id")
            ->select(\DB::raw("m_company.name as company_name, m_company_license.start_date as start_date, m_company_license.end_date as end_date, m_company_license.license_type as license_type, m_company_license.status as status, m_company_license.payment_amount as payment, m_company_license.discount_amount as discount"))
            ->where(function ($q) use ($request) {
                if (! empty($request->select_company_name)) {
                    $q->where("m_company.id", "=", $request->select_company_name);
                }
                if (! empty($request->from_date)) {
                    $q->whereDate('m_company_license.start_date', '>=', $request->from_date);
                }

                if (! empty($request->to_date)) {
                    $q->whereDate('m_company_license.end_date', '<=', $request->to_date);
                }
            })
            ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
        return $companylicense;
    }

    /**
     * Get company reprt data from storage
     *
     * @param \Illuminate\Http\CompanyReportRequest $request
     * @return Object or array
     */
    public function getCompanyReportData($request)
    {
        $company = DB::table("m_company")
            ->select(\DB::raw("m_company.name as company_name, m_company.address, m_company.phone_number_1 as primary_phone, m_company.phone_number_2 as secondary_phone"))
            ->where(function ($q) use ($request) {
                if (! empty($request->select_company_name)) {
                    $q->where("m_company.id", "=", $request->select_company_name);
                }
                if (! empty($request->from_date)) {
                    $q->whereDate('m_company.create_datetime', '>=', $request->from_date);
                }

                if (! empty($request->to_date)) {
                    $q->whereDate('m_company.create_datetime', '<=', $request->to_date);
                }
            })
            ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
        return $company;
    }

    /**
     * Get invoice report data from storage
     *
     * @param \Illuminate\Http\SaleReportRequest $request
     * @return Object or array
     */
    public function getInvoiceReportData($request)
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
            ->groupBy("t_sale.id", "m_shop.name")
            ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
        return $sale;
    }

    /**
     * Get invoice details report data from storage
     *
     * @param \Illuminate\Http\InvoiceDetailsReportRequest $request
     * @return Object or array
     */
    public function getInvoiceDetailsReportData($request)
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
        return $sale;
    }

    /**
     * Get sale return report data from storage
     *
     * @param \Illuminate\Http\SaleReportRequest $request
     * @return Object or array
     */
    public function getSaleReturnReportData($request)
    {
        $salereturn = ReturnDetail::join("t_return", "t_return.id", "=", "t_return_details.return_id")
            ->join("t_sale", "t_sale.id", "=", "t_return.sale_id")
            ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->join("m_product", "m_product.id", "=", "t_return_details.product_id")
            ->select(\DB::raw("t_sale.sale_date as sale_date,t_return.return_date as return_date, sum(t_return_details.quantity) as quantity"))
            ->where(function ($q) use ($request) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }
                if (! empty($request->from_date) && ! empty($request->to_date)) {
                    $q->whereBetween('t_return.return_date', [$request->from_date, $request->to_date]);
                }
            })
            ->groupBy("return_id", "sale_date", "return_date")
            ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
        return $salereturn;
    }

    /**
     * Get damage loss report data from storage
     *
     * @param \Illuminate\Http\DamageLossReportRequest $request
     * @return Object or array
     */
    public function getDamageLossReportData($request)
    {
        $damage_loss = DamageDetail::join("t_damage_loss", "t_damage_loss.id", "=", "t_damage_loss_details.damage_loss_id")
            ->leftjoin("m_product", "m_product.id", "=", "t_damage_loss_details.product_id")
            ->leftjoin("m_shop", "m_shop.id", "=", "t_damage_loss.shop_id")

            ->select(\DB::raw("m_shop.name as shop_name,m_product.name as product_name,sum(t_damage_loss_details.quantity) as damageloss_quantity, t_damage_loss_details.product_status as type"))

            ->where(function ($q) use ($request) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }
                if (! empty($request->damage)) {
                    $q->where("t_damage_loss_details.product_status", "=", $request->damage);
                }
                if (! empty($request->loss)) {
                    $q->where("t_damage_loss_details.product_status", "=", $request->loss);
                }
                if (! empty($request->from_date)) {
                    $q->whereDate('t_damage_loss.damage_loss_date', '>=', $request->from_date);
                }
                if (! empty($request->to_date)) {
                    $q->whereDate('t_damage_loss.damage_loss_date', '<=', $request->to_date);
                }
            })
            ->whereNull("t_damage_loss.warehouse_id")
            ->groupBy("t_damage_loss_details.product_id", "shop_name", "product_name", "type")
            ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
        return $damage_loss;
    }

    /**
     * Get inventory stock category report data from storage
     *
     * @param \Illuminate\Http\InventoryStockCategoryRequest $request
     * @return Object or array
     */
    public function getInventoryStockCategoryReportData($request)
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
            ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
        return $stockCategory;
    }

    /**
     * Get inventory stock product report data from storage
     *
     * @param \Illuminate\Http\InventoryStockProductRequest $request
     * @return Object or array
     */
    public function getInventoryStockProductReportData($request)
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
            ->paginate($request->custom_pg_size == "" ? config('constants.REPORT_PAGINATION') : $request->custom_pg_size);
        return $stockProduct;
    }
}
