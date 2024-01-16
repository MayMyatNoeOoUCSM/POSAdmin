<?php

namespace App\Contracts\Services;

interface OrderServiceInterface
{

    /**
     * Get pending order count search by terminal id
     *
     * @param  Integer $terminalId
     * @return Integer $orderCount
     */
    public function getPendingOrderCount($terminalId);

    /**
     * Get order list
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $orderList
     */
    public function getOrderList($request);

    /**
     * Get order lists that have been confirmed invoice
     *
     * @param  \App\Http\Requests\Request $request
     * @return Object $orderList
     */
    public function getConfirmedOrderList($request);

    /**
     * Get order info search by id
     *
     * @param  Integer $order_id
     * @return Object $orderInfo
     */
    public function getOrderInfoById($order_id);

    /**
     * Cancel Order
     *
     * @param  \App\Http\Requests\OrderRequest  $request
     * @param  Object $orderDetails
     * @return Boolean
     */
    public function cancelOrder($request, $orderDetails);

    /**
     * Update product quantity (warehouse shop product table) in storage by adding quantity
     *
     * @param  Integer $shop_id
     * @param  Object $info
     * @return Boolean
     */
    public function updateQtyPlus($shop_id, $info);

    /**
     * Get total order amount for today
     *
     * @return Object or boolean
     */
    public function getTodayOrderTotal();

    /**
     * Get total order amount for yesterday
     *
     * @return Object or boolean
     */
    public function getYesterdayOrderTotal();

    /**
     * Get total order amount for current month
     *
     * @return Object or boolean
     */
    public function getMonthOrderTotal();

    /**
     * Get total order amount for current year
     *
     * @return Object or boolean
     */
    public function getYearOrderTotal();

    /**
     * Get order date, total order amount and shop name for today
     *
     * @return Object or boolean
     */
    public function getTodayOrderShops();

    /**
     * Get all total order amount
     *
     * @return Integer or boolean
     */
    public function getOrderTotal();

    /**
     * Get order list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $orderList or null
     */
    public function getOrderListForExport($request);

    /**
     * Get weekly order data
     *
     * @return Array
     */
    public function getWeeklyReport();

    /**
     * Get monthly order data
     *
     * @return Array
     */
    public function getMonthlyReport();

    /**
     * merge shop, terminal, staff and order info
     *
     * @param  Object $order
     * @param  Object $orderInfo
     * @param  Integer $shopId
     * @return Object
     */
    public function mergeOrderDetailsInfo($order, $orderInfo, $shopId);
    
    /**
     * Get Order Details from table
     *
     * @return Object $orderDetails
    */
    public function getOrderDetails($request);
   

    /**
     * Accept order by cashier
     *
     * @return Boolean
    */
    public function acceptOrderByCashier($request);

    /**
     * Invoice order by cashier
     *
     * @return Boolean
    */
    public function invoiceOrderByCashier($request);

    /**
     * Get Order Pending List from storage
     *
     * @return Object Order Pending List
     */
    public function getPendingList($request);

    /**
     * Get Order Invoice Recent List from storage
     *
     * @return Object OrderList
     */
    public function getInvoiceRecentList($request);

    /**
     * Get order invoice details
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $invoiceDetails
     */
    public function getOrderInvoiceDetails($request);

    /**
     * Get order list at kitchen
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $orderList
     */
    public function getOrderKitchenList($request);

    /**
     * Confirm order at kitchen
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function confirmOrderByKitchen($request);

    /**
     * Get current order status search by id
     *
     * @param  Integer $id
     * @return Object
     */
    public function getCurrentOrderStatus($id);

    /**
    * Get order restaurant table list at kitchen
    *
    * @param  \Illuminate\Http\Request $request
    * @return Object
    */
    public function getOrderKitchenRestaurantTableList($request);

    /**
     * Insert order info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @return Integer $order_id
     */
    public function insertOrder($request);

   
    /**
     * Check order exist by order id
     *
     * @param  Integer $order_id
     * @return Boolean
     */
    public function checkOrderIDExists($order_id);

    /**
    * Confirm Order Invoice Details
    *
    * @param  App\Http\Requests\Api\OrderInvoiceDetailsRequest $request
    * @return Object
    */
    public function confirmOrderInvoiceDetails($request);
}
