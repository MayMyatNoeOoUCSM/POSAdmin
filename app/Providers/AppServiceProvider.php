<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $baseUrlDaoInterface = 'App\Contracts\Dao';
        $baseUrlDao = 'App\Dao';
        $baseUrlServiceInterface = 'App\Contracts\Services';
        $baseUrlService = 'App\Services';

        $this->app->bind($baseUrlDaoInterface . '\StaffDaoInterface', $baseUrlDao . '\StaffDao');
        $this->app->bind($baseUrlServiceInterface . '\StaffServiceInterface', $baseUrlService . '\StaffService');
        $this->app->bind($baseUrlDaoInterface . '\WarehouseDaoInterface', $baseUrlDao . '\WarehouseDao');
        $this->app->bind($baseUrlServiceInterface . '\WarehouseServiceInterface', $baseUrlService . '\WarehouseService');
        $this->app->bind($baseUrlDaoInterface . '\ShopDaoInterface', $baseUrlDao . '\ShopDao');
        $this->app->bind($baseUrlServiceInterface . '\ShopServiceInterface', $baseUrlService . '\ShopService');
        $this->app->bind($baseUrlDaoInterface . '\TerminalDaoInterface', $baseUrlDao . '\TerminalDao');
        $this->app->bind($baseUrlServiceInterface . '\TerminalServiceInterface', $baseUrlService . '\TerminalService');
        $this->app->bind($baseUrlDaoInterface . '\CategoryDaoInterface', $baseUrlDao . '\CategoryDao');
        $this->app->bind($baseUrlServiceInterface . '\CategoryServiceInterface', $baseUrlService . '\CategoryService');
        $this->app->bind($baseUrlDaoInterface . '\SaleDaoInterface', $baseUrlDao . '\SaleDao');
        $this->app->bind($baseUrlDaoInterface . '\SaleDetailDaoInterface', $baseUrlDao . '\SaleDetailDao');
        $this->app->bind($baseUrlServiceInterface . '\SaleServiceInterface', $baseUrlService . '\SaleService');
        $this->app->bind($baseUrlServiceInterface . '\SaleReturnServiceInterface', $baseUrlService . '\SaleReturnService');
        $this->app->bind($baseUrlServiceInterface . '\ReturnDetailServiceInterface', $baseUrlService . '\ReturnDetailService');
        $this->app->bind($baseUrlServiceInterface . '\DamageLossServiceInterface', $baseUrlService . '\DamageLossService');
        $this->app->bind($baseUrlDaoInterface . '\SaleReturnDaoInterface', $baseUrlDao . '\SaleReturnDao');
        $this->app->bind($baseUrlDaoInterface . '\ReturnDetailDaoInterface', $baseUrlDao . '\ReturnDetailDao');
        $this->app->bind($baseUrlDaoInterface . '\DamageLossDaoInterface', $baseUrlDao . '\DamageLossDao');

        $this->app->bind($baseUrlDaoInterface . '\ProductDaoInterface', $baseUrlDao . '\ProductDao');
        $this->app->bind($baseUrlServiceInterface . '\ProductServiceInterface', $baseUrlService . '\ProductService');
        $this->app->bind($baseUrlDaoInterface . '\StockDaoInterface', $baseUrlDao . '\StockDao');
        $this->app->bind($baseUrlServiceInterface . '\StockServiceInterface', $baseUrlService . '\StockService');

        $this->app->bind($baseUrlServiceInterface . '\SaleDetailServiceInterface', $baseUrlService . '\SaleDetailService');
        $this->app->bind($baseUrlDaoInterface . '\DamageDetailDaoInterface', $baseUrlDao . '\DamageDetailDao');
        $this->app->bind($baseUrlServiceInterface . '\DamageDetailServiceInterface', $baseUrlService . '\DamageDetailService');

        $this->app->bind($baseUrlDaoInterface . '\ReportDaoInterface', $baseUrlDao . '\ReportDao');
        $this->app->bind($baseUrlServiceInterface . '\ReportServiceInterface', $baseUrlService . '\ReportService');

        $this->app->bind($baseUrlDaoInterface . '\ProductReportDaoInterface', $baseUrlDao . '\ProductReportDao');
        $this->app->bind($baseUrlServiceInterface . '\ProductReportServiceInterface', $baseUrlService . '\ProductReportService');

        $this->app->bind($baseUrlDaoInterface . '\RestaurantDaoInterface', $baseUrlDao . '\RestaurantDao');
        $this->app->bind($baseUrlServiceInterface . '\RestaurantServiceInterface', $baseUrlService . '\RestaurantService');

        $this->app->bind($baseUrlDaoInterface . '\CompanyDaoInterface', $baseUrlDao . '\CompanyDao');
        $this->app->bind($baseUrlServiceInterface . '\CompanyServiceInterface', $baseUrlService . '\CompanyService');

        $this->app->bind($baseUrlDaoInterface . '\CompanyLicenseDaoInterface', $baseUrlDao . '\CompanyLicenseDao');
        $this->app->bind($baseUrlServiceInterface . '\CompanyLicenseServiceInterface', $baseUrlService . '\CompanyLicenseService');

        $this->app->bind($baseUrlDaoInterface . '\OrderDaoInterface', $baseUrlDao . '\OrderDao');
        $this->app->bind($baseUrlServiceInterface . '\OrderServiceInterface', $baseUrlService . '\OrderService');

        $this->app->bind($baseUrlDaoInterface . '\OrderDetailsDaoInterface', $baseUrlDao . '\OrderDetailsDao');
        $this->app->bind($baseUrlServiceInterface . '\OrderDetailsServiceInterface', $baseUrlService . '\OrderDetailsService');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ///Schema::defaultStringLength(191);
    }
}
