<?php

namespace App\Contracts\Dao;

/**
 * SaleDaoInterface
 */
interface SaleDaoInterface
{

    /**
     * Get Pending Sales Count from storage
     *
     * @param Integer $terminalId
     * @return Integer count
     */
    public function getPendingSalesCount($terminalId);

    /**
     * Check Invoice Exists in Sale
     *
     * @param Integer $invoiceNo
     * @return boolean
     */
    public function invoiceExistsInSaleReturn($invoiceNo);

    /**
     * Get Sale List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object saleList
     */
    public function getSaleList($request);

    /**
     * Get Confirmed(Invoice Status:Confirmed) Sale List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object saleList
     */
    public function getConfirmedSaleList($request);

    /**
     * Get SaleIno by Id from storage
     *
     * @param Integer $sale_id
     * @return Object saleInfo
     */
    public function getSaleInfoById($sale_id);

    /**
     * Cancel Sale Invoice
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     *
     */
    public function cancelSaleInvoice($request);

    /**
     * Update Quantity into storage
     *
     * @param Integer $shop_id
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function updateQtyPlus($shop_id, $request);

    /**
     * Get Total Sale For Today
     *
     * @return Object
     */
    public function getTodaySaleTotal();

    /**
     * Get Total Sale For Yesterday
     *
     * @return Object
     */
    public function getYesterdaySaleTotal();

    /**
     * Get Total Sale For Month
     *
     * @return Object
     */
    public function getMonthSaleTotal();

    /**
     * Get Total Sale For Year
     *
     * @return Object
     */
    public function getYearSaleTotal();

    /**
     * Get Total Sale Shop For Today
     *
     * @return Object
     */
    public function getTodaySaleShops();

    /**
     * Get Sale Info By Sales Date
     *
     * @param Date $sale_dates
     * @return Object
     */
    public function getSaleDataByDates($sales_dates);

    /**
     * Get Sale Monthly Info By FromDate ToDate
     *
     * @param Date $from_date
     * @param Date $to_date
     * @return Object
     */
    public function getMonthlySale($from_date, $to_date);

    /**
     * Get Total Sales
     *
     * @return Object
     */
    public function getSaleTotal();

    /**
     * Get Sales List For Excel Export
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getSaleListForExport($request);

    /**
     * Get sale category list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleList
     */
    public function getSaleCategoryListForExport($request);

    /**
     * Get sale product list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $salePorductList
     */
    public function getTopSaleProductListForExport($request);

    /**
     * Get sale product list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $salePorductList
     */
    public function getSaleProductListForExport($request);

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
     * Best Selling Product Info
     *
     * @return Object
     */
    public function bestSellingProduct();

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
