<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
//Clear configurations:
// Route::get('/config-clear', function () {
//     $status = Artisan::call('config:clear');
//     return '<h1>Configurations cleared</h1>';
// });
// //Clear cache:
// Route::get('/cache-clear', function () {
//     $status = Artisan::call('cache:clear');
//     return '<h1>Cache cleared</h1>';
// });
// //Clear configuration cache:
// Route::get('/config-cache', function () {
//     $status = Artisan::call('config:Cache');
//     return '<h1>Configurations cache cleared</h1>';
// });
// Route::get('/notify', function () {
//     $user = \App\Models\Staff::find(1);

//     $messageInfo = [
//             'body' => 'Test Low Stock',
//             'type' => 1,
//     ];

//     $user->notify(new \App\Notifications\AdminNotification($messageInfo));

//     return dd("Done");
// });

Route::get('/', function () {
    return redirect(app()->getLocale());
});
Route::group(['middleware' => ['setlocale','XssSanitizer']], function () {
    Route::get('/', 'AuthController@getLogin')->name('login.getLogin');
    Route::post('post/login', 'AuthController@postLogin')->name('login.postLogin');
    Route::group(['middleware' => ['auth:staff']], function () {
        Route::get('/logout', 'AuthController@logout')->name('logout');

        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        
        Route::get('/markAsRead', function () {
            auth()->user()->unreadNotifications->markAsRead();
        })->name('mark');

        // Route::get('checknoti', function () {
        //     session(['checknoti'=>"check"]);
        // });

        Route::group(['middleware' => 'isSuperAdmin'], function () {
            Route::get('company/login/{company}', 'CompanyController@loginByCompanyAdmin')->name('company.login.companyadmin');

            Route::get('company', 'CompanyController@index')->name('company');
            Route::get('company/create', 'CompanyController@create')->name('company.create');
            Route::post('company/store', 'CompanyController@store')->name('company.store');
            Route::get('company/{company}/edit', 'CompanyController@edit')->name('company.edit');
            Route::put('company/{company}', 'CompanyController@update')->name('company.update');
            Route::delete('company/{company}', 'CompanyController@delete')->name('company.delete');

            Route::get('company/license', 'CompanyLicenseController@index')->name('company.license');
            Route::get('company/license/create', 'CompanyLicenseController@create')->name('company.license.create');
            Route::post('company/license/store', 'CompanyLicenseController@store')->name('company.license.store');
            Route::get('company/license/{license}/edit', 'CompanyLicenseController@edit')->name('company.license.edit');
            Route::put('company/license/{license}', 'CompanyLicenseController@update')->name('company.license.update');
            Route::delete('company/license/{license}', 'CompanyLicenseController@delete')->name('company.license.delete');

            Route::get('staff', 'StaffController@index')->name('staff');
            Route::get('staff/create', 'StaffController@create')->name('staff.create');
            Route::post('staff/store', 'StaffController@store')->name('staff.store');
            Route::get('staff/{staff}/edit', 'StaffController@edit')->name('staff.edit');
            Route::put('staff/{staff}', 'StaffController@update')->name('staff.update');
            Route::delete('staff/{staff}', 'StaffController@delete')->name('staff.delete');

            Route::get('company/report', 'ReportController@companyReport')->name('company.report');
            Route::get('companylicense/report', 'ReportController@companyLicenseReport')->name('companylicense.report');
        });

        Route::group(['middleware' => 'isCompanyAdmin'], function () {
            Route::get('/warehouse', 'WarehouseController@index')->name('warehouse');
            Route::get('warehouse/create', 'WarehouseController@create')->name('warehouse.create');
            Route::post('warehouse/store', 'WarehouseController@store')->name('warehouse.store');
            Route::get('/warehouse/{warehouse}/edit', 'WarehouseController@edit')->name('warehouse.edit');
            Route::put('/warehouse/{warehouse}', 'WarehouseController@update')->name('warehouse.update');
            Route::delete('/warehouse/{warehouse}', 'WarehouseController@delete')->name('warehouse.delete');

            Route::get('/shop', 'ShopController@index')->name('shop');
            Route::get('shop/create', 'ShopController@create')->name('shop.create');
            Route::post('shop/store', 'ShopController@store')->name('shop.store');
            Route::get('/shop/{shop}/edit', 'ShopController@edit')->name('shop.edit');
            Route::put('/shop/{shop}', 'ShopController@update')->name('shop.update');
            Route::delete('/shop/{shop}', 'ShopController@delete')->name('shop.delete');

            Route::get('/report', 'ReportController@index')->name('report');
        });

        Route::group(['middleware' => 'isShopAdmin'], function () {
            Route::get('/terminal', 'TerminalController@index')->name('terminal');
            Route::get('terminal/create', 'TerminalController@create')->name('terminal.create');
            Route::post('terminal/store', 'TerminalController@store')->name('terminal.store');
            Route::get('/terminal/{terminal}/edit', 'TerminalController@edit')->name('terminal.edit');
            Route::put('/terminal/{terminal}', 'TerminalController@update')->name('terminal.update');
            Route::delete('/terminal/{terminal}', 'TerminalController@delete')->name('terminal.delete');

            Route::get('/restaurant', 'RestaurantController@index')->name('restaurant');
            Route::get('restaurant/create', 'RestaurantController@create')->name('restaurant.create');
            Route::post('restaurant/store', 'RestaurantController@store')->name('restaurant.store');
            Route::get('/restaurant/{restaurant}/edit', 'RestaurantController@edit')->name('restaurant.edit');
            Route::put('/restaurant/{restaurant}', 'RestaurantController@update')->name('restaurant.update');
            Route::delete('/restaurant/{restaurant}', 'RestaurantController@delete')->name('restaurant.delete');

            Route::get('/category', 'CategoryController@index')->name('category');
            Route::get('category/create', 'CategoryController@create')->name('category.create');
            Route::post('category/store', 'CategoryController@store')->name('category.store');
            Route::get('/category/{category}/edit', 'CategoryController@edit')->name('category.edit');
            Route::put('/category/{category}', 'CategoryController@update')->name('category.update');
            Route::delete('/category/{category}', 'CategoryController@delete')->name('category.delete');

            Route::get('/product', 'ProductController@index')->name('product');
            Route::get('product/create', 'ProductController@create')->name('product.create');
            Route::post('product/store', 'ProductController@store')->name('product.store');
            Route::get('/product/{product}/edit', 'ProductController@edit')->name('product.edit');
            Route::put('/product/{product}', 'ProductController@update')->name('product.update');
            Route::get('/product/{product}/clone', 'ProductController@clone')->name('product.clone');
            Route::post('/product/store/clone', 'ProductController@storeCloneProduct')->name('product.store.clone');

            Route::post('product/change_min_qty', 'ProductController@changeMinQty')->name('product.change_min_qty');
            Route::delete('/product/{product}', 'ProductController@delete')->name('product.delete');
            Route::get('product/change_price', 'ProductController@changePrice')->name('product.change_price');
            Route::post('product/update_price', 'ProductController@updatePrice')->name('product.update_price');
            Route::post('product/import_excel', 'ProductController@importExcel')->name('product.import_excel');

            Route::get('/stock', 'StockController@index')->name('stock');
            Route::get('stock/create', 'StockController@create')->name('stock.create');
            Route::post('stock/store', 'StockController@store')->name('stock.store');
            Route::get('stock/get_sub_category', 'StockController@get_sub_category')->name('stock.get_sub_category');
            Route::get('stock/get_product', 'StockController@get_product')->name('stock.get_product');
            Route::get('stock/search_product', 'StockController@search_product')->name('stock.search_product');
            Route::get('stock/transfer/{warehouse_id}/{shop_id}/{product_id}', 'StockController@transfer')->name('stock.transfer');
            Route::post('stock/transfer_store', 'StockController@transferStore')->name('stock.transfer_store');

            Route::get('/damage_loss', 'DamageLossController@index')->name('damageloss');
            Route::get('damage_loss/create', 'DamageLossController@create')->name('damageloss.create');
            Route::get('damage_loss/details', 'DamageLossController@details')->name('damageloss.details');
            Route::post('damage_loss/store', 'DamageLossController@store')->name('damageloss.store');

            Route::get('/sale', 'SaleController@index')->name('sale');
            //Route::post('/sale', 'SaleController@index')->name('sale');
            Route::get('/sale/{sale}/edit', 'SaleController@edit')->name('sale.edit');
            Route::get('/sale/{sale}/download', 'SaleController@download')->name('sale.download');
            Route::put('/sale/{sale}/cancel_invoice', 'SaleController@cancelInvoice')->name('sale.cancel_invoice');

            Route::get('/sale_return', 'SaleReturnController@index')->name('sale_return');
            Route::get('sale_return/create', 'SaleReturnController@indexSaleInvoice')->name('sale_return.create');
            Route::get('/sale_return/{sale}/get_sale_detail', 'SaleReturnController@get_sale_detail')->name('sale_return.get_sale_detail');
            Route::post('sale_return/store', 'SaleReturnController@store')->name('sale_return.store');
            Route::get('sale_return/details', 'SaleReturnController@details')->name('salereturn.details');

           

            Route::get('order', 'OrderController@index')->name('order');
            Route::get('order/create', 'OrderController@create')->name('order.create');
            Route::post('order/store', 'OrderController@store')->name('order.store');
            Route::get('order/{order}/edit', 'OrderController@edit')->name('order.edit');
            Route::put('order/{order}', 'OrderController@update')->name('order.update');
            Route::put('order/{order}/cancel_order', 'OrderController@cancelOrder')->name('order.cancel_order');

            Route::get('sale/report', 'ReportController@saleReport')->name('sale.report');
            Route::get('invoice/report', 'ReportController@invoiceReport')->name('invoice.report');
            Route::get('invoice/details/report', 'ReportController@invoiceDetailsReport')->name('invoice.details.report');
            Route::get('salereturn/report', 'ReportController@saleReturnReport')->name('salereturn.report');
            Route::get('damageloss/report', 'ReportController@damageLossReport')->name('damageloss.report');
            Route::get('product/report', 'ReportController@productReport')->name('product.report');
            Route::get('salecategory/report', 'ReportController@saleCategoryReport')->name('salecategory.report');
            Route::get('saleproduct/report', 'ReportController@saleProductReport')->name('saleproduct.report');
            Route::get('top_saleproduct/report', 'ReportController@topSaleProductReport')->name('topsale.report');
            Route::get('inventory_product/report', 'ReportController@stockInventoryProductReport')->name('inventoryproduct.report');
            Route::get('inventory_category/report', 'ReportController@stockInventoryCategoryReport')->name('inventorycategory.report');
        });
    });
});
