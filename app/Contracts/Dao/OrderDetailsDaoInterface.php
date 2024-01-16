<?php

namespace App\Contracts\Dao;

interface OrderDetailsDaoInterface
{
    /**
     * Insert order details info in storage
     *
     * @param Integer $order_id
     * @param  \Illuminate\Http\Request $request
     * @return Integer $order_id
     */
    public function insertOrderDetails($order_id, $request);

    /**
     * Get Pending Order Count from Order
     *
     * @param Integer $productId
     * @return Integer count
     */
    public function getOrderDetailsByProductId($productId);

    /**
     * Get Order Detail by OrderId from storage
     *
     * @param Integer $orderId
     * @return Object orderDetailList
     */
    public function getOrderDetailsByOrderId($orderId);

    /**
     * Get Order Details List from storage
     *
     * @param Illuminate\Http\Request $request
     * @return Object $orderDetailsList
     */
    public function getOrderDetailsList($request);

    /**
     * Confirm order details at kitchen
     *
     * @param \Illuminate\Http\OrderDetailsRequest $request
     * @return Boolean
     */
    public function confirmOrderByKitchen($request);

    /**
     * Get current order details status search by id
     *
     * @param  Integer $id
     * @return Object
     */
    public function getCurrentOrderDetailsStatus($id);

    /**
     * Get Order Details List from storage
     *
     * @param Illuminate\Http\OrderDetailsRequest $request
     * @return Object $orderDetailsList
     */
    public function getOrderConfirmItems($request);
}
