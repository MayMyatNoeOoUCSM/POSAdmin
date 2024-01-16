<?php

namespace App\Services;

use App\Contracts\Dao\SaleDaoInterface;
use App\Contracts\Dao\SaleDetailDaoInterface;
use App\Contracts\Services\SaleServiceInterface;

class SaleService implements SaleServiceInterface
{
    private $salesDao;
    private $salesDetailsDao;
    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\SalesDaoInterface $salesDao
     * @param \App\Contracts\Dao\SaleDetailDaoInterface $salesDetailsDao
     *
     * @return void
     */
    public function __construct(SaleDaoInterface $salesDao, SaleDetailDaoInterface $salesDetailsDao)
    {
        $this->salesDao = $salesDao;
        $this->salesDetailsDao = $salesDetailsDao;
    }

    /**
     * Get pending sale invoice count search by terminal id
     *
     * @param  Integer $terminalId
     * @return Integer $salesCount
     */
    public function getPendingSalesCount($terminalId)
    {
        return $this->salesDao->getPendingSalesCount($terminalId);
    }

    /**
     * Get sale list
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $saleList
     */
    public function getSaleList($request)
    {
        return $this->salesDao->getSaleList($request);
    }

    /**
     * Get sale lists that have been confirmed invoice
     *
     * @param  \App\Http\Requests\SaleReturnInvoiceListRequest $request
     * @return Object $saleList
     */
    public function getConfirmedSaleList($request)
    {
        return $this->salesDao->getConfirmedSaleList($request);
    }

    /**
     * Cancel Sale Invoice
     *
     * @param  \App\Http\Requests\SaleRequest  $request
     * @param  Object $saleDetails
     * @return Boolean
     */
    public function cancelSaleInvoice($request, $saleDetails)
    {
        $this->groupUpdateQtyPlus($request, $saleDetails);
        return $this->salesDao->cancelSaleInvoice($request);
    }

    /**
     * Update product quantity (warehouse shop product table) in storage by adding quantity
     *
     * @param  Integer $shop_id
     * @param  Object $info
     * @return Boolean
     */
    public function updateQtyPlus($shop_id, $info)
    {
        return $this->salesDao->updateQtyPlus($shop_id, $info);
    }

    /**
     * Get sale return exists check by invoice number
     *
     * @param  String $invoiceNo
     * @return Boolean
     */
    public function invoiceExistsInSaleReturn($invoiceNo)
    {
        return $this->salesDao->invoiceExistsInSaleReturn($invoiceNo);
    }

    /**
     * Get sale info search by id
     *
     * @param  Integer $sale_id
     * @return Object $saleInfo
     */
    public function getSaleInfoById($sale_id)
    {
        return $this->salesDao->getSaleInfoById($sale_id);
    }

    /**
     * Get total sale amount for today
     *
     * @return Object or boolean
     */
    public function getTodaySaleTotal()
    {
        return $this->salesDao->getTodaySaleTotal();
    }

    /**
     * Get total sale amount for yesterday
     *
     * @return Object or boolean
     */
    public function getYesterdaySaleTotal()
    {
        return $this->salesDao->getYesterdaySaleTotal();
    }

    /**
     * Get total sale amount for current month
     *
     * @return Object or boolean
     */
    public function getMonthSaleTotal()
    {
        return $this->salesDao->getMonthSaleTotal();
    }

    /**
     * Get total sale amount for current year
     *
     * @return Object or boolean
     */
    public function getYearSaleTotal()
    {
        return $this->salesDao->getYearSaleTotal();
    }

    /**
     * Get sale date, total sale amount and shop name for today
     *
     * @return Object or boolean
     */
    public function getTodaySaleShops()
    {
        return $this->salesDao->getTodaySaleShops();
    }

    /**
     * Get weekly sales data
     *
     * @return Array
     */
    public function getWeeklyReport()
    {
        $firstdate = Date("Y-m-d", strtotime("-6 day"));
        $lastdate = Date("Y-m-d");
        $period = new \DatePeriod(new \DateTime($firstdate), new \DateInterval('P1D'), new \DateTime($lastdate . "+1 day"));
        foreach ($period as $date) {
            $currentweeklydates[] = date('jS', strtotime($date->format("Y-m-d")));
            $dates[] = $date->format("Y-m-d");
        }
        $sale = $this->salesDao->getSaleDataByDates($dates);

        $currentweeklydata = [0, 0, 0, 0, 0, 0, 0];
        $i = 0;
        foreach ($dates as $value) {
            foreach ($sale as $value1) {
                if ($value == $value1['sale_date']) {
                    $currentweeklydata[$i] = $value1['sum'];
                }
            }
            $i++;
        }

        $oldfirstdate = Date("Y-m-d", strtotime("-13 day"));
        $oldlastdate = Date("Y-m-d", strtotime("-6 day"));
        $oldperiod = new \DatePeriod(new \DateTime($oldfirstdate), new \DateInterval('P1D'), new \DateTime($oldlastdate));
        foreach ($oldperiod as $date) {
            $olddates[] = $date->format("Y-m-d");
        }
        $oldsale = $this->salesDao->getSaleDataByDates($olddates);

        $oldweeklydata = [0, 0, 0, 0, 0, 0, 0];
        $i = 0;
        foreach ($olddates as $value) {
            foreach ($oldsale as $value1) {
                if ($value == $value1['sale_date']) {
                    $oldweeklydata[$i] = $value1['sum'];
                }
            }
            $i++;
        }
        if (array_sum($currentweeklydata) > array_sum($oldweeklydata)) {
            $difference = array_sum($currentweeklydata) - array_sum($oldweeklydata);
            $weeklyProgressPercentage = $difference * 100 / array_sum($currentweeklydata);
        } elseif (array_sum($currentweeklydata) < array_sum($oldweeklydata)) {
            $difference = array_sum($oldweeklydata) - array_sum($currentweeklydata);
            $weeklyProgressPercentage = $difference * 100 / array_sum($oldweeklydata);
        } else {
            $weeklyProgressPercentage = 0;
        }

        $weeklyreport = [
            "currentWeeklyData" => $currentweeklydata,
            "currentWeeklyDates" => $currentweeklydates,
            "oldWeeklyData" => $oldweeklydata,
            "currentWeeklyDataTotal" => array_sum($currentweeklydata),
            "weeklyProgress" => array_sum($currentweeklydata) > array_sum($oldweeklydata) ? "up" : "down",
            "weeklyProgressPercentage" => $weeklyProgressPercentage,

        ];
        return $weeklyreport;
    }

    /**
     * Get monthly sales data
     *
     * @return Array
     */
    public function getMonthlyReport()
    {
        $until = new \DateTime();
        $until1 = new \DateTime();

        // current month and previous 5 months
        $interval = new \DateInterval('P5M'); //5 months
        $from = $until->sub($interval);
        $f = $from->format("n");
        $l = $until1->format("n");

        // month number array
        $monthlycurrent = [];
        for ($f; $f <= $l; $f++) {
            $monthlycurrent[] = (int) $f;
        }

        $f1 = $from->format("Y-m-01");
        $l1 = $until1->format("Y-m-d");
        $dateInterval = new \DateInterval('P1M');
        $datePeriod = new \DatePeriod(new \DateTime($f1), $dateInterval, new \DateTime($l1));

        // monthly label array
        foreach ($datePeriod as $date) {
            $monthlyLabel[] = $date->format("M");
        }

        // return sale month number (12) and sum (3000.00) for each month
        $monthlycurrentsale = $this->salesDao->getMonthlySale($from->format("Y-m-01"), $until1->format("Y-m-d"));

        // temp array
        $currentMonthlySaleData = [0, 0, 0, 0, 0, 0];
        $i = 0;
        foreach ($monthlycurrent as $v) {
            if ($monthlycurrentsale != false) {
                foreach ($monthlycurrentsale as $v1) {
                    // check if sale_month is equal , insert to temp array with index
                    if ($v == $v1['sale_month']) {
                        $currentMonthlySaleData[$i] = $v1['sum'];
                    }
                }
                $i++;
            }
        }

        $lastyear = (int) date("Y") - 1;
        $lastyearMonthlysale = $this->salesDao->getMonthlySale($from->format($lastyear . "-m-01"), $until1->format($lastyear . "-m-d"));

        $lastyearMonthlySaleData = [0, 0, 0, 0, 0, 0];
        $i = 0;
        foreach ($monthlycurrent as $v) {
            if ($lastyearMonthlysale != false) {
                foreach ($lastyearMonthlysale as $v1) {
                    if ($v == $v1['sale_month']) {
                        $lastyearMonthlySaleData[$i] = $v1['sum'];
                    }
                }
                $i++;
            }
        }

        if (array_sum($currentMonthlySaleData) > array_sum($lastyearMonthlySaleData)) {
            $difference = array_sum($currentMonthlySaleData) - array_sum($lastyearMonthlySaleData);
            $YearlyProgressPercentage = $difference * 100 / array_sum($currentMonthlySaleData);
        } elseif (array_sum($currentMonthlySaleData) < array_sum($lastyearMonthlySaleData)) {
            $difference = array_sum($lastyearMonthlySaleData) - array_sum($currentMonthlySaleData);
            $YearlyProgressPercentage = $difference * 100 / array_sum($lastyearMonthlySaleData);
        } else {
            $YearlyProgressPercentage = 0;
        }

        $monthlyreport = [
            "currentMonthlySaleData" => $currentMonthlySaleData,
            "lastyearMonthlySaleData" => $lastyearMonthlySaleData,
            "monthlyLabel" => $monthlyLabel,
            "currentYearlyDataTotal" => array_sum($currentMonthlySaleData),
            "YearlyProgress" => array_sum($currentMonthlySaleData) > array_sum($lastyearMonthlySaleData) ? "up" : "down",
            "YearlyProgressPercentage" => $YearlyProgressPercentage,
        ];
        return $monthlyreport;
    }

    /**
     * Get all total sales amount
     *
     * @return Integer
     */
    public function getSaleTotal()
    {
        return $this->salesDao->getSaleTotal();
    }

    /**
     * Get sale list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleList
     */
    public function getSaleListForExport($request)
    {
        return $this->salesDao->getSaleListForExport($request);
    }

    /**
     * Get sale category list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleList
     */
    public function getSaleCategoryListForExport($request)
    {
        return $this->salesDao->getSaleCategoryListForExport($request);
    }

    /**
     * Get sale category list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleList
     */
    public function getSaleProductListForExport($request)
    {
        return $this->salesDao->getSaleProductListForExport($request);
    }

    /**
     * Get sale product list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $salePorductList
     */
    public function getTopSaleProductListForExport($request)
    {
        return $this->salesDao->getTopSaleProductListForExport($request);
    }

    /**
     * Get invoice list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $invoiceList
     */
    public function getInvoiceDataExport($request)
    {
        return $this->salesDao->getInvoiceDataExport($request);
    }

    /**
     * Get invoice detail list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $invoiceDetailList
     */
    public function getInvoiceDetailDataExport($request)
    {
        return $this->salesDao->getInvoiceDetailDataExport($request);
    }

    /**
     * Get best selling product info
     *
     * @return Object $product
     */
    public function bestSellingProduct()
    {
        return $this->salesDao->bestSellingProduct();
    }

    /**
     * merge shop, terminal, staff and sale info
     *
     * @param  Object $sale
     * @param  Object $saleInfo
     * @param  Integer $shopId
     * @return Object
     */
    public function mergeSaleDetailsInfo($sale, $saleInfo, $shopId)
    {
        // check sale info and add shop_name,terminal_name,staff_name and total to sale
        if (! empty($saleInfo) && count($saleInfo) > 0) {
            $sale->shop_name = $saleInfo[0]->shop_name;
            $sale->terminal_name = $saleInfo[0]->terminal_name;
            $sale->staff_name = $saleInfo[0]->staff_name;
            $sale->total = $saleInfo[0]->total;
        }

        // check shop id and add shop id to sale
        if (! empty($shopId) && count($shopId) > 0) {
            $sale->shop_id = $shopId[0];
        }

        return $sale;
    }

    /**
     * Insert sale info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @return Integer $sale_id
     */
    public function insertSale($request)
    {
        $insertSale = $this->salesDao->insertSale($request);
        if (is_numeric($insertSale)) {
            return $this->salesDetailsDao->insertSaleDetails($insertSale, $request);
        }
        return false;
    }

    /**
     * Check sale exist by sale id
     *
     * @param  Integer $sale_id
     * @return Boolean
     */
    public function checkSaleIDExists($sale_id)
    {
        return $this->salesDao->checkSaleIDExists($sale_id);
    }

    /**
     * Invoice sale by sale staff
     *
     * @return Boolean
     */
    public function invoiceSale($request)
    {
        return $this->salesDao->invoiceSale($request);
    }

    /**
     * Get Sale Invoice Recent List from storage
     *
     * @return Object SaleList
     */
    public function getInvoiceRecentList($request)
    {
        return $this->salesDao->getInvoiceRecentList($request);
    }

    /**
     * Get Sale Invoice Details from table
     *
     * @return Object $invoiceDetails
     */
    public function getSaleInvoiceDetails($request)
    {
        return $this->salesDao->getSaleInvoiceDetails($request);
    }

    /**
     * Get Sale Invoice Pending List from storage
     *
     * @return Object SaleList
     */
    public function getInvoicePendingList($request)
    {
        return $this->salesDao->getInvoicePendingList($request);
    }

    /**
     * Confirm Sale Invoice Details
     * @param  App\Http\Requests\Api\SaleInvoiceDetailsRequest $request
     * @return Object
     */
    public function confirmSaleInvoiceDetails($request)
    {
        return $this->salesDao->confirmSaleInvoiceDetails($request);
    }

    private function groupUpdateQtyPlus($request, $saleDetails)
    {
        foreach ($saleDetails as $saleInfo) {
            $this->salesDao->updateQtyPlus($request->shop_id, $saleInfo);
        }
    }
}
