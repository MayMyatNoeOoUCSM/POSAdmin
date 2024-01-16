<?php

namespace App\Contracts\Dao;

interface OrderDaoInterface
{
    /**
     * Get Pending Order Count from storage
     *
     * @param Integer $terminalId
     * @return Integer count
     */
    public function getPendingOrderCount($terminalId);

    /**
     * Get Order List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object orderList
     */
    public function getOrderList($request);

    /**
     * Get Confirmed(Order Status:Confirmed) Order List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object orderList
     */
    public function getConfirmedOrderList($request);

    /**
     * Get OrderIno by Id from storage
     *
     * @param Integer $order_id
     * @return Object orderInfo
     */
    public function getOrderInfoById($order_id);

    /**
     * Cancel Order
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     *
     */
    public function cancelOrder($request);

    /**
     * Update Quantity into storage
     *
     * @param Integer $shop_id
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function updateQtyPlus($shop_id, $request);

    /**
     * Get Total Order For Today
     *
     * @return Object
     */
    public function getTodayOrderTotal();

    /**
     * Get Total Order For Yesterday
     *
     * @return Object
     */
    public function getYesterdayOrderTotal();

    /**
     * Get Total Order For Month
     *
     * @return Object
     */
    public function getMonthOrderTotal();

    /**
     * Get Total Order For Year
     *
     * @return Object
     */
    public function getYearOrderTotal();

    /**
     * Get Total Order Shop For Today
     *
     * @return Object
     */
    public function getTodayOrderShops();

    /**
     * Get Order Info By Order Date
     *
     * @param Date $order_date
     * @return Object
     */
    public function getOrderDataByDates($order_date);

    /**
     * Get Order Monthly Info By FromDate ToDate
     *
     * @param Date $from_date
     * @param Date $to_date
     * @return Object
     */
    public function getMonthlyOrder($from_date, $to_date);

    /**
     * Get Total Order
     *
     * @return Object
     */
    public function getOrderTotal();

    /**
     * Get Order List For Excel Export
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getOrderListForExport($request);
    

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
