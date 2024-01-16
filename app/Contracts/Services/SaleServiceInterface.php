<?php

namespace App\Contracts\Services;

interface SaleServiceInterface
{

    /**
     * Get pending sale invoice count search by terminal id
     *
     * @param  Integer $terminalId
     * @return Integer $salesCount
     */
    public function getPendingSalesCount($terminalId);

    /**
     * Get sale return exists check by invoice number
     *
     * @param  String $invoiceNo
     * @return Boolean
     */
    public function invoiceExistsInSaleReturn($invoiceNo);

    /**
     * Get sale list
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $saleList
     */
    public function getSaleList($request);

    /**
     * Get sale lists that have been confirmed invoice
     *
     * @param  \App\Http\Requests\SaleReturnInvoiceListRequest $request
     * @return Object $saleList
     */
    public function getConfirmedSaleList($request);

    /**
     * Get sale info search by id
     *
     * @param  Integer $sale_id
     * @return Object $saleInfo
     */
    public function getSaleInfoById($sale_id);

    /**
     * Cancel Sale Invoice
     *
     * @param  \App\Http\Requests\SaleRequest  $request
     * @param  Object $saleDetails
     * @return Boolean
     */
    public function cancelSaleInvoice($request, $saleDetail);

    /**
     * Update product quantity (warehouse shop product table) in storage by adding quantity
     *
     * @param  Integer $shop_id
     * @param  Object $info
     * @return Boolean
     */
    public function updateQtyPlus($shop_id, $info);

    /**
     * Get total sale amount for today
     *
     * @return Object or boolean
     */
    public function getTodaySaleTotal();

    /**
     * Get total sale amount for yesterday
     *
     * @return Object or boolean
     */
    public function getYesterdaySaleTotal();

    /**
     * Get total sale amount for current month
     *
     * @return Object or boolean
     */
    public function getMonthSaleTotal();

    /**
     * Get total sale amount for current year
     *
     * @return Object or boolean
     */
    public function getYearSaleTotal();

    /**
     * Get sale date, total sale amount and shop name for today
     *
     * @return Object or boolean
     */
    public function getTodaySaleShops();

    /**
     * Get all total sales amount
     *
     * @return Integer or boolean
     */
    public function getSaleTotal();

    /**
     * Get sale list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleList
     */
    public function getSaleListForExport($request);

    /**
     * Get sale category list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleCategoryList
     */
    public function getSaleCategoryListForExport($request);

    /**
     * Get sale product list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $salePorductList
     */
    public function getSaleProductListForExport($request);

    /**
     * Get sale product list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $salePorductList
     */
    public function getTopSaleProductListForExport($request);

    /**
     * Get invoice list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $invoiceList
     */
    public function getInvoiceDataExport($request);

    /**
     * Get invoice detail list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $invoiceDetailList
     */
    public function getInvoiceDetailDataExport($request);

    /**
     * Get best selling product info
     *
     * @return Object $product
     */
    public function bestSellingProduct();

    /**
     * Get weekly sales data
     *
     * @return Array
     */
    public function getWeeklyReport();

    /**
     * Get monthly sales data
     *
     * @return Array
     */
    public function getMonthlyReport();

    /**
     * merge shop, terminal, staff and sale info
     *
     * @param  Object $sale
     * @param  Object $saleInfo
     * @param  Integer $shopId
     * @return Object
     */
    public function mergeSaleDetailsInfo($sale, $saleInfo, $shopId);

    /**
     * Insert sale info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @return Integer $sale_id
     */
    public function insertSale($request);

    /**
     * Check sale exist by sale id
     *
     * @param  Integer $sale_id
     * @return Boolean
     */
    public function checkSaleIDExists($sale_id);

    /**
     * Invoice sale by sale staff
     *
     * @return Boolean
     */
    public function invoiceSale($request);

    /**
     * Get Sale Invoice Recent List from storage
     *
     * @return Object SaleList
     */
    public function getInvoiceRecentList($request);

    /**
     * Get Sale Invoice Details from table
     *
     * @return Object $invoiceDetails
     */
    public function getSaleInvoiceDetails($request);

    /**
     * Get Sale Invoice Pending List from storage
     *
     * @return Object SaleList
     */
    public function getInvoicePendingList($request);

    /**
    * Confirm Sale Invoice Details
    * @param  App\Http\Requests\Api\SaleInvoiceDetailsRequest $request
    * @return Object
    */
    public function confirmSaleInvoiceDetails($request);
}
