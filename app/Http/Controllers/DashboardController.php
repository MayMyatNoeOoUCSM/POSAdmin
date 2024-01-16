<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CompanyServiceInterface;
use App\Contracts\Services\OrderServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Contracts\Services\SaleServiceInterface;
use App\Contracts\Services\ShopServiceInterface;
use App\Contracts\Services\StaffServiceInterface;
use App\Contracts\Services\StockServiceInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $productService;
    private $saleService;
    private $stockService;
    private $shopService;
    private $orderService;
    private $companyService;
    private $staffService;


    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\ProductServiceInterface $productService
     * @param \App\Contracts\Services\SaleServiceInterface $saleService
     * @param \App\Contracts\Services\StockServiceInterface $stockService
     * @param \App\Contracts\Services\ShopServiceInterface $shopService
     * @param \App\Contracts\Services\OrderServiceInterface $orderService
     * @param \App\Contracts\Services\CompanyServiceInterface $companyService
     * @param \App\Contracts\Services\StaffServiceInterface $staffService
     *
     *
     * @return void
     */
    public function __construct(ProductServiceInterface $productService, SaleServiceInterface $saleService, StockServiceInterface $stockService, ShopServiceInterface $shopService, OrderServiceInterface $orderService, CompanyServiceInterface $companyService, StaffServiceInterface $staffService)
    {
        $this->productService = $productService;
        $this->saleService = $saleService;
        $this->stockService = $stockService;
        $this->shopService = $shopService;
        $this->orderService = $orderService;
        $this->companyService = $companyService;
        $this->staffService = $staffService;
    }

    /**
     *
     * Show Dasboard List
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     */
    public function index(Request $request)
    {
        // check super admin
        if (auth()->user()->role == config('constants.ADMIN')) {
            // total company count
            $totalCompany   = $this->companyService->getTotalCompany();
            // total active company count
            $totalActiveCompany   = $this->companyService->getTotalActiveCompany();
            // total expire company count
            $totalExpireCompany   = $this->companyService->getTotalExpireCompany();
            // total users count
            $totalUsers = $this->staffService->getStaffNo()->count();
            $checkShopType=null;
            $weeklyreport = [];
            $monthlyreport = [];
            return view('dashboard', compact('totalCompany', 'totalActiveCompany', 'totalExpireCompany', 'totalUsers', 'checkShopType', 'weeklyreport', 'monthlyreport'));
        }
        // return restaurant, retails or both
        $checkShopType = $this->shopService->getShopTypeByCompanyID(auth()->user()->company_id);

        if ($checkShopType == 'retails') {
            //today sale total list
            $todaySaleTotal   = $this->saleService->getTodaySaleTotal();
            //month's sale total list
            $monthSaleTotal   = $this->saleService->getMonthSaleTotal();
            //year's sale total list
            $yearSaleTotal   = $this->saleService->getYearSaleTotal();
            //today sale shops list
            $todaySaleShops = $this->saleService->getTodaySaleShops();
            //weekly sale report
            $weeklyreport  = $this->saleService->getWeeklyReport();
            //monthly sale report
            $monthlyreport  = $this->saleService->getMonthlyReport();
            //sale total list
            $saleTotal  = $this->saleService->getSaleTotal();
            
            return view('dashboard', compact('todaySaleTotal', 'monthSaleTotal', 'yearSaleTotal', 'weeklyreport', 'monthlyreport', 'todaySaleShops', 'saleTotal', 'checkShopType'));
        }

        if ($checkShopType == 'restaurant') {
            //today order total list
            $todayOrderTotal   = $this->orderService->getTodayOrderTotal();
            //month's order total list
            $monthOrderTotal   = $this->orderService->getMonthOrderTotal();
            //year's Order total list
            $yearOrderTotal   = $this->orderService->getYearOrderTotal();
            //weekly Order report
            $weeklyreport  = $this->orderService->getWeeklyReport();
            //monthly Order report
            $monthlyreport  = $this->orderService->getMonthlyReport();
            //order total list
            $orderTotal  = $this->orderService->getOrderTotal();
            return view('dashboard', compact('todayOrderTotal', 'monthOrderTotal', 'yearOrderTotal', 'weeklyreport', 'monthlyreport', 'orderTotal', 'checkShopType'));
        }

        if ($checkShopType == 'both') {
            //today sale total list
            $todaySaleTotal   = $this->saleService->getTodaySaleTotal();
            //month's sale total list
            $monthSaleTotal   = $this->saleService->getMonthSaleTotal();
            //year's sale total list
            $yearSaleTotal   = $this->saleService->getYearSaleTotal();
            //sale total list
            $saleTotal  = $this->saleService->getSaleTotal();
            
            //today order total list
            $todayOrderTotal   = $this->orderService->getTodayOrderTotal();
            //month's order total list
            $monthOrderTotal   = $this->orderService->getMonthOrderTotal();
            //year's Order total list
            $yearOrderTotal   = $this->orderService->getYearOrderTotal();
            //order total list
            $orderTotal  = $this->orderService->getOrderTotal();

            $weeklyreport = [];
            $monthlyreport = [];

            return view('dashboard', compact('todaySaleTotal', 'monthSaleTotal', 'yearSaleTotal', 'saleTotal', 'todayOrderTotal', 'monthOrderTotal', 'yearOrderTotal', 'orderTotal', 'checkShopType', 'weeklyreport', 'monthlyreport'));
        }
    }
}
