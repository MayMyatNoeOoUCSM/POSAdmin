<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'Api\AuthController@postLogin');

Route::group(['middleware' => ['auth:api','checkCompanyLicense']], function () {


    // Cashier
    Route::group(['middleware' => 'isCashier'], function () {
        Route::get('order', 'Api\OrderController@index');
        Route::post('cashier/order/confirm/invoice', 'Api\OrderController@orderConfirmInvoice');
        Route::post('cashier/order/invoice', 'Api\OrderController@invoice');
        Route::post('cashier/invoice/recent/list', 'Api\OrderController@invoiceRecentList');
        Route::post('cashier/invoice/details', 'Api\OrderController@invoiceDetails');
        Route::post('cashier/restaurant/table/list', 'Api\RestaurantTableController@restaurantTableListAtCashier');
        Route::post('order/confirm/items', 'Api\OrderDetailsController@orderConfirmItems');
    });
   

    // Kitchen
    Route::group(['middleware' => 'isKitchen'], function () {
        Route::post('order/kitchen/list', 'Api\OrderDetailsController@orderDetailsListAtKitchen');
        Route::post('kitchen/restaurant/table/list', 'Api\RestaurantTableController@restaurantTableListAtKitchen');
        Route::post('order/kitchen/confirm', 'Api\OrderDetailsController@orderDetailsConfirmAtKitchen');
    });

    // Waiter
    Route::group(['middleware' => 'isWaiter'], function () {
        Route::post('product/search', 'Api\ProductController@searchProduct');
        Route::post('product/category/list', 'Api\ProductController@productCategoryList');
        Route::post('product/list/bycategoryid', 'Api\ProductController@productListByCategoryID');
        Route::post('product/searchby/productcode', 'Api\ProductController@searchProductByCode');
        Route::post('order/create', 'Api\OrderController@orderCreate');
        Route::post('order/update', 'Api\OrderController@orderUpdate');
    });

    Route::group(['middleware' => 'isSale'], function () {
        Route::post('sale/product/search', 'Api\ProductController@searchProduct');
        Route::post('sale/product/category/list', 'Api\ProductController@productCategoryList');
        Route::post('sale/product/list/bycategoryid', 'Api\ProductController@productListByCategoryID');
        Route::post('sale/product/searchby/productcode', 'Api\ProductController@searchProductByCode');
        Route::post('sale/create', 'Api\SaleController@saleCreate');
        Route::post('sale/update', 'Api\SaleController@saleUpdate');
        Route::post('sale/remove', 'Api\SaleController@saleRemove');
        Route::post('sale/confirm/invoice', 'Api\SaleController@saleConfirmInvoice');
        Route::post('sale/invoice', 'Api\SaleController@invoice');
        Route::post('sale/invoice/recent/list', 'Api\SaleController@invoiceRecentList');
        Route::post('sale/invoice/details', 'Api\SaleController@invoiceDetails');
        Route::post('sale/invoice/pending/list', 'Api\SaleController@invoicePendingList');
    });

    Route::post('order/details', 'Api\OrderController@details');
    Route::get('restaurant/table', 'Api\RestaurantTableController@index');
    Route::post('staff/details', 'Api\StaffController@details');
    Route::post('terminal/list', 'Api\TerminalController@terminalList');
});
