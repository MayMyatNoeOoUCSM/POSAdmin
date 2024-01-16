<?php

namespace App\Services;

use App\Contracts\Dao\OrderDaoInterface;
use App\Contracts\Dao\OrderDetailsDaoInterface;
use App\Contracts\Services\OrderDetailsServiceInterface;

class OrderDetailsService implements OrderDetailsServiceInterface
{
    private $orderDetailsDao;

    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\OrderDetailDaoInterface $orderDetailsDao
     * @return void
     */
    public function __construct(OrderDetailsDaoInterface $orderDetailsDao)
    {
        $this->orderDetailsDao = $orderDetailsDao;
    }
    /**
     * Insert order details info in storage
     *
     * @param Integer $order_id
     * @param  \Illuminate\Http\Request $request
     * @return Integer $order_id
     */
    public function insertOrderDetails($order_id, $request)
    {
        return $this->orderDetailsDao->insertOrderDetails($order_id, $request);
    }

    /**
     * Get total order count search by product id
     *
     * @param Integer $productId
     * @return Integer
     */
    public function getOrderDetailsByProductId($productId)
    {
        return $this->orderDetailsDao->getOrderDetailByProductId($productId);
    }

    /**
     * Get order details list
     *
     * @param  Integer $orderId
     * @return Object $orderDetailList
     */
    public function getOrderDetailsByOrderId($orderId)
    {
        return $this->orderDetailsDao->getOrderDetailsByOrderId($orderId);
    }

    /**
     * Get Order Details List from storage
     *
     * @param Illuminate\Http\Request $request
     * @return Object $orderDetailsList
     */
    public function getOrderDetailsList($request)
    {
        return $this->orderDetailsDao->getOrderDetailsList($request);
    }

    /**
    * Confirm order details at kitchen
    *
    * @param \Illuminate\Http\OrderDetailsRequest $request
    * @return Boolean
    */
    public function confirmOrderByKitchen($request)
    {
        $checkOrderStatus = $this->getCurrentOrderDetailsStatus($request->id);
        if ($checkOrderStatus->order_details_status== config('constants.ORDER_DETAILS_CREATE')) {
            return $this->orderDetailsDao->confirmOrderByKitchen($request);
        }
        return false;
    }

    /**
    * Get current order details status search by id
    *
    * @param  Integer $id
    * @return Object
    */
    public function getCurrentOrderDetailsStatus($id)
    {
        return $this->orderDetailsDao->getCurrentOrderDetailsStatus($id);
    }

    /**
     * Get Order Details List from storage
     *
     * @param Illuminate\Http\OrderDetailsRequest $request
     * @return Object $orderDetailsList
     */
    public function getOrderConfirmItems($request)
    {
        return $this->orderDetailsDao->getOrderConfirmItems($request);
    }
}
