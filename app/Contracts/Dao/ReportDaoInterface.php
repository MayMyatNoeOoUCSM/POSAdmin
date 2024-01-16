<?php

namespace App\Contracts\Dao;

/**
 * ReportDaoInterface
 */
interface ReportDaoInterface
{
    /**
     * Get report data from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getData($request);

    /**
     * Get data for report export
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getDataExport($request);

    /**
     * Get sale report data from storage
     *
     * @param \Illuminate\Http\SaleReportRequest $request
     * @return Object or array
     */
    public function getSaleReportData($request, $date);


    /**
     * Get sale report data from storage
     *
     * @param \Illuminate\Http\SaleCategoryReportRequest $request
     * @return Object or array
     */
    public function getSaleCategoryReportData($request);

    /**
     * Get sale report data from storage
     *
     * @param \Illuminate\Http\SaleCategoryReportRequest $request
     * @return Object or array
     */
    public function getSaleProductReportData($request);

    /**
     * Get top sale report data from storage
     *
     * @param \Illuminate\Http\SaleCategoryReportRequest $request
     * @return Object or array
     */
    public function getTopSaleProductReportData($request);

    /**
     * Get product report data from storage
     *
     * @param \Illuminate\Http\ProductReportRequest $request
     * @return Object or array
     */
    public function getProductReportData($request);

    /**
     * Get company license report data from storage
     *
     * @param \Illuminate\Http\CompanyLicenseReportRequest $request
     * @return Object or array
     */
    public function getCompanyLicenseReportData($request);

    /**
     * Get company reprt data from storage
     *
     * @param \Illuminate\Http\CompanyReportRequest $request
     * @return Object or array
     */
    public function getCompanyReportData($request);

    /**
     * Get invoice report data from storage
     *
     * @param \Illuminate\Http\SaleReportRequest $request
     * @return Object or array
     */
    public function getInvoiceReportData($request);

    /**
     * Get invoice details report data from storage
     *
     * @param \Illuminate\Http\InvoiceDetailsReportRequest $request
     * @return Object or array
     */
    public function getInvoiceDetailsReportData($request);

    /**
     * Get sale return report data from storage
     *
     * @param \Illuminate\Http\SaleReportRequest $request
     * @return Object or array
     */
    public function getSaleReturnReportData($request);

    /**
     * Get damage loss report data from storage
     *
     * @param \Illuminate\Http\DamageLossReportRequest $request
     * @return Object or array
     */
    public function getDamageLossReportData($request);

    /**
     * Get inventory stock category report data from storage
     *
     * @param \Illuminate\Http\InventoryStockCategoryRequest $request
     * @return Object or array
     */
    public function getInventoryStockCategoryReportData($request);

    /**
     * Get inventory stock product report data from storage
     *
     * @param \Illuminate\Http\InventoryStockProductRequest $request
     * @return Object or array
     */
    public function getInventoryStockProductReportData($request);
}
