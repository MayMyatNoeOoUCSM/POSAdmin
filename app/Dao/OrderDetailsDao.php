<?php

namespace App\Dao;

use App\Contracts\Dao\OrderDetailsDaoInterface;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Support\Facades\Auth;

class OrderDetailsDao implements OrderDetailsDaoInterface
{

    /**
     * Insert order details info in storage
     *
     * @param Integer $order_id
     * @param  \Illuminate\Http\Request $request
     * @return Integer $order_id
     */
    public function insertOrderDetails($order_id, $request)
    {
        for ($i = 0; $i < count($request->data['product_id']); $i++) {
            $orderDetails = new OrderDetails();
            $orderDetails->order_id = $order_id;
            $orderDetails->product_id = $request->data['product_id'][$i];
            $orderDetails->price = $request->data['price'][$i];
            $orderDetails->quantity = $request->data['quantity'][$i];
            $orderDetails->remark = $request->data['remark'][$i];
            $orderDetails->save();
        }
        return $order_id;
    }

    /**
     * Get total order count search by product id
     *
     * @param Integer $productId
     * @return Integer
     */
    public function getOrderDetailsByProductId($productId)
    {
        $orderDetailCount = OrderDetails::where('product_id', $productId)->count();
        return $orderDetailCount;
    }

    /**
     * Get order details list
     *
     * @param  Integer $orderId
     * @return Object $orderDetailList
     */
    public function getOrderDetailsByOrderId($orderId)
    {
        $orderDetailList = Order::where('t_order.id', $orderId)
            ->join("t_order_details as od", "od.order_id", "=", "t_order.id")
            ->join('m_product as p', 'p.id', '=', 'od.product_id')
            ->select(
                "od.*",
                "p.name as product_name"
            )
            ->groupBy(["od.id", "product_name"])
            ->get();

        return $orderDetailList;
    }

    /**
     * Get Order Details List from storage
     *
     * @param Illuminate\Http\Request $request
     * @return Object $orderDetailsList
     */
    public function getOrderDetailsList($request)
    {
        $orderDetailsList = OrderDetails::rightjoin('t_order', 't_order.id', '=', 't_order_details.order_id')
            ->where('t_order.order_status', '!=', config('constants.ORDER_INVOICE'))
            ->where('t_order_details.order_details_status', '=', config('constants.ORDER_DETAILS_CREATE'))
            ->where('t_order.shop_id', Auth::user()->shop_id)
            ->select('t_order_details.*')
            ->paginate(config('constants.ORDER_PAGINATION'));
        return $orderDetailsList;
    }

    /**
     * Confirm order details at kitchen
     *
     * @param \Illuminate\Http\OrderDetailsRequest $request
     * @return Object $orderDetails
     */
    public function confirmOrderByKitchen($request)
    {
        $orderDetails = OrderDetails::join(
            't_order',
            't_order.id',
            '=',
            't_order_details.order_id'
        )
            ->where('t_order_details.id', $request->id)
            ->where('t_order.shop_id', Auth::user()->shop_id)
            ->update([
                't_order_details.order_details_status' => config('constants.ORDER_DETAILS_CONFIRM'),
            ]);
        Order::where('id', OrderDetails::find($request->id)->order_id)
            ->where('order_status', config('constants.ORDER_CREATE'))
            ->where('shop_id', Auth::user()->shop_id)
            ->update([
                'order_status' => config('constants.ORDER_CONFIRM'),
            ]);
        return $orderDetails;
    }

    /**
     * Get current order details status search by id
     *
     * @param  Integer $id
     * @return Object $orderDetails
     */
    public function getCurrentOrderDetailsStatus($id)
    {
        $orderDetails = OrderDetails::where('id', $id)->first();
        return $orderDetails;
    }

    /**
     * Get Order Details List from storage
     *
     * @param Illuminate\Http\OrderDetailsRequest $request
     * @return Object $orderDetailsList
     */
    public function getOrderConfirmItems($request)
    {
        $orderDetailsList = OrderDetails::where('order_details_status', '=', config('constants.ORDER_DETAILS_CONFIRM'))
            ->where('order_id', $request->id)
            ->paginate(config('constants.ORDER_PAGINATION'));
        return $orderDetailsList;
    }
}
