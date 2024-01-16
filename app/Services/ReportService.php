<?php

namespace App\Services;

use App\Contracts\Dao\ReportDaoInterface;
use App\Contracts\Services\ReportServiceInterface;

class ReportService implements ReportServiceInterface
{
    private $reportDao;

    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\ReportDaoInterface $reportDao
     * @return void
     */
    public function __construct(ReportDaoInterface $reportDao)
    {
        $this->reportDao = $reportDao;
    }

    /**
     * Get report data from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getData($request)
    {
        return $this->reportDao->getData($request);
    }

    /**
     * Get data for report export excel from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object or array
     */
    public function getDataExport($request)
    {
        return $this->reportDao->getDataExport($request);
    }

    /**
     * Get sale report data from storage
     *
     * @param \Illuminate\Http\SaleReportRequest $request
     * @return Object or array
     */
    public function getSaleReportData($request)
    {
        if ($request->from_date !="" and $request->to_date !="") {
            $period = new \DatePeriod(
                new \DateTime($request->from_date),
                new \DateInterval('P1D'),
                new \DateTime(date('Y-m-d', strtotime($request->to_date.'+ 1 day')))
            );
            $dataLabel = [];
            foreach ($period as $key => $value) {
                $dataLabel[] =$value->format('Y-m-d');
            }
            $dataList  = [];
            foreach ($dataLabel as $value) {
                $dataList[] =$this->reportDao
                    ->getSaleReportData($request, $value)->totalamount??0;
            }
            return ['dataLabel'=>$dataLabel,'dataList'=>$dataList];
        }
        return ['dataLabel'=>[],'dataList'=>[]];
    }

    /**
     * Get sale report data from storage
     *
     * @param \Illuminate\Http\SaleCategoryReportRequest $request
     * @return Object or array
     */
    public function getSaleCategoryReportData($request)
    {
        return $this->reportDao->getSaleCategoryReportData($request);
    }

    /**
     * Get sale report data from storage
     *
     * @param \Illuminate\Http\SaleProductReportRequest $request
     * @return Object or array
     */
    public function getSaleProductReportData($request)
    {
        return $this->reportDao->getSaleProductReportData($request);
    }

    /**
     * Get top sale report data from storage
     *
     * @param \Illuminate\Http\SaleCategoryReportRequest $request
     * @return Object or array
     */
    public function getTopSaleProductReportData($request)
    {
        return $this->reportDao->getTopSaleProductReportData($request);
    }

    /**
     * Get product report data from storage
     *
     * @param \Illuminate\Http\ProductReportRequest $request
     * @return Object or array
     */
    public function getProductReportData($request)
    {
        return $this->reportDao->getProductReportData($request);
    }

    /**
     * Get company license report data from storage
     *
     * @param \Illuminate\Http\CompanyLicenseReportRequest $request
     * @return Object or array
     */
    public function getCompanyLicenseReportData($request)
    {
        return $this->reportDao->getCompanyLicenseReportData($request);
    }

    /**
     * Get company license report data from storage
     *
     * @param \Illuminate\Http\CompanyReportRequest $request
     * @return Object or array
     */
    public function getCompanyReportData($request)
    {
        return $this->reportDao->getCompanyReportData($request);
    }

    /**
     * Get invoice report data from storage
     *
     * @param \Illuminate\Http\SaleReportRequest $request
     * @return Object or array
     */
    public function getInvoiceReportData($request)
    {
        return $this->reportDao->getInvoiceReportData($request);
    }

    /**
     * Get invoice details report data from storage
     *
     * @param \Illuminate\Http\InvoiceDetailsReportRequest $request
     * @return Object or array
     */
    public function getInvoiceDetailsReportData($request)
    {
        return $this->reportDao->getInvoiceDetailsReportData($request);
    }

    /**
     * Get sale return report data from storage
     *
     * @param \Illuminate\Http\SaleReportRequest $request
     * @return Object or array
     */
    public function getSaleReturnReportData($request)
    {
        return $this->reportDao->getSaleReturnReportData($request);
    }

    /**
     * Get damage loss report data from storage
     *
     * @param \Illuminate\Http\DamageLossReportRequest $request
     * @return Object or array
     */
    public function getDamageLossReportData($request)
    {
        return $this->reportDao->getDamageLossReportData($request);
    }

    /**
     * Get inventory stock category report data from storage
     *
     * @param \Illuminate\Http\InventoryStockCategoryRequest $request
     * @return Object or array
     */
    public function getInventoryStockCategoryReportData($request)
    {
        return $this->reportDao->getInventoryStockCategoryReportData($request);
    }

    /**
     * Get inventory stock product report data from storage
     *
     * @param \Illuminate\Http\InventoryStockProductRequest $request
     * @return Object or array
     */
    public function getInventoryStockProductReportData($request)
    {
        return $this->reportDao->getInventoryStockProductReportData($request);
    }
}
