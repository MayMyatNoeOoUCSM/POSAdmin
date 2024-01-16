<?php

namespace App\Services;

use App\Contracts\Dao\OrderDaoInterface;
use App\Contracts\Dao\OrderDetailsDaoInterface;
use App\Contracts\Services\OrderServiceInterface;

class OrderService implements OrderServiceInterface
{
    private $orderDao;
    private $orderDetailsDao;

    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\OrderDaoInterface $orderDao
     * @param \App\Contracts\Dao\OrderDetailDaoInterface $orderDetailsDao
     *
     * @return void
     */
    public function __construct(OrderDaoInterface $orderDao, OrderDetailsDaoInterface $orderDetailsDao)
    {
        $this->orderDao = $orderDao;
        $this->orderDetailsDao = $orderDetailsDao;
    }

    /**
     * Get pending order invoice count search by terminal id
     *
     * @param  Integer $terminalId
     * @return Integer $salesCount
     */
    public function getPendingOrderCount($terminalId)
    {
        return $this->orderDao->getPendingOrderCount($terminalId);
    }

    /**
     * Get sale list
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $orderDao
     */
    public function getOrderList($request)
    {
        return $this->orderDao->getOrderList($request);
    }

    /**
     * Get order lists that have been confirmed invoice
     *
     * @param  \App\Http\Requests\OrderRequest $request
     * @return Object $orderList
     */
    public function getConfirmedOrderList($request)
    {
        return $this->orderDao->getConfirmedOrderList($request);
    }

    /**
     * Cancel Sale Invoice
     *
     * @param  \App\Http\Requests\OrderRequest  $request
     * @param  Object $orderDetails
     * @return Boolean
     */
    public function cancelOrder($request, $orderDetails)
    {
        $this->groupUpdateQtyPlus($request, $orderDetails);
        return $this->orderDao->cancelOrder($request);
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
        return $this->orderDao->updateQtyPlus($shop_id, $info);
    }

    /**
     * Get order info search by id
     *
     * @param  Integer $order_id
     * @return Object $orderInfo
     */
    public function getOrderInfoById($order_id)
    {
        return $this->orderDao->getOrderInfoById($order_id);
    }

    /**
     * Get total order amount for today
     *
     * @return Object or boolean
     */
    public function getTodayOrderTotal()
    {
        return $this->orderDao->getTodayOrderTotal();
    }

    /**
     * Get total order amount for yesterday
     *
     * @return Object or boolean
     */
    public function getYesterdayOrderTotal()
    {
        return $this->orderDao->getYesterdayOrderTotal();
    }

    /**
     * Get total order amount for current month
     *
     * @return Object or boolean
     */
    public function getMonthOrderTotal()
    {
        return $this->orderDao->getMonthOrderTotal();
    }

    /**
     * Get total order amount for current year
     *
     * @return Object or boolean
     */
    public function getYearOrderTotal()
    {
        return $this->orderDao->getYearOrderTotal();
    }

    /**
     * Get order date, total order amount and shop name for today
     *
     * @return Object or boolean
     */
    public function getTodayOrderShops()
    {
        return $this->orderDao->getTodayOrderShops();
    }

    /**
     * Get weekly order data
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
        $order = $this->orderDao->getOrderDataByDates($dates);

        $currentweeklydata = [0, 0, 0, 0, 0, 0, 0];
        $i = 0;
        foreach ($dates as $value) {
            foreach ($order as $value1) {
                if ($value == $value1['order_date']) {
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
        $oldorder = $this->orderDao->getOrderDataByDates($olddates);

        $oldweeklydata = [0, 0, 0, 0, 0, 0, 0];
        $i = 0;
        foreach ($olddates as $value) {
            foreach ($oldorder as $value1) {
                if ($value == $value1['order_date']) {
                    $oldweeklydata[$i] = $value1['sum'];
                }
            }
            $i++;
        }
        if (array_sum($currentweeklydata) > array_sum($oldweeklydata)) {
            $difference = array_sum($currentweeklydata) - array_sum($oldweeklydata);
            $weeklyProgressPercentage = 100 * $difference / array_sum($currentweeklydata);
        } elseif (array_sum($currentweeklydata) < array_sum($oldweeklydata)) {
            $difference = array_sum($oldweeklydata) - array_sum($currentweeklydata);
            $weeklyProgressPercentage = 100 * $difference / array_sum($oldweeklydata);
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
     * Get monthly order data
     *
     * @return Array
     */
    public function getMonthlyReport()
    {
        $until = new \DateTime();
        $until1 = new \DateTime();

        $interval = new \DateInterval('P5M'); //5 months
        $from = $until->sub($interval);
        $f = $from->format("n");
        $l = $until1->format("n");

        $monthlycurrent = [];
        for ($f; $f <= $l; $f++) {
            $monthlycurrent[] = (int) $f;
        }

        $f1 = $from->format("Y-m-01");
        $l1 = $until1->format("Y-m-d");
        $dateInterval = new \DateInterval('P1M');
        $datePeriod = new \DatePeriod(new \DateTime($f1), $dateInterval, new \DateTime($l1));

        foreach ($datePeriod as $date) {
            $monthlyLabel[] = $date->format("M");
        }

        $monthlycurrentorder = $this->orderDao->getMonthlyOrder($from->format("Y-m-01"), $until1->format("Y-m-d"));

        $currentMonthlyOrderData = [0, 0, 0, 0, 0, 0];
        $i = 0;
        foreach ($monthlycurrent as $v) {
            foreach ($monthlycurrentorder as $v1) {
                if ($v == $v1['order_month']) {
                    $currentMonthlyOrderData[$i] = $v1['sum'];
                }
            }
            $i++;
        }

        $lastyear = (int) date("Y") - 1;
        $lastyearMonthlyorder = $this->orderDao->getMonthlyOrder($from->format($lastyear . "-m-01"), $until1->format($lastyear . "-m-d"));

        $lastyearMonthlyOrderData = [0, 0, 0, 0, 0, 0];
        $i = 0;
        foreach ($monthlycurrent as $v) {
            foreach ($lastyearMonthlyorder as $v1) {
                if ($v == $v1['order_month']) {
                    $lastyearMonthlyOrderData[$i] = $v1['sum'];
                }
            }
            $i++;
        }
        if (array_sum($currentMonthlyOrderData) > array_sum($lastyearMonthlyOrderData)) {
            $difference = array_sum($currentMonthlyOrderData) - array_sum($lastyearMonthlyOrderData);
            $YearlyProgressPercentage = 100 * $difference / array_sum($currentMonthlyOrderData);
        } elseif (array_sum($currentMonthlyOrderData) < array_sum($lastyearMonthlyOrderData)) {
            $difference = array_sum($lastyearMonthlyOrderData) - array_sum($currentMonthlyOrderData);
            $YearlyProgressPercentage = 100 * $difference / array_sum($lastyearMonthlyOrderData);
        } else {
            $YearlyProgressPercentage = 0;
        }

        $monthlyreport = [
            "currentMonthlyOrderData" => $currentMonthlyOrderData,
            "lastyearMonthlyOrderData" => $lastyearMonthlyOrderData,
            "monthlyLabel" => $monthlyLabel,
            "currentYearlyDataTotal" => array_sum($currentMonthlyOrderData),
            "YearlyProgress" => array_sum($currentMonthlyOrderData) > array_sum($lastyearMonthlyOrderData) ? "up" : "down",
            "YearlyProgressPercentage" => $YearlyProgressPercentage,
        ];
        return $monthlyreport;
    }

    /**
     * Get all total order amount
     *
     * @return Integer or boolean
     */
    public function getOrderTotal()
    {
        return $this->orderDao->getOrderTotal();
    }

    /**
     * Get order list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $orderList or null
     */
    public function getOrderListForExport($request)
    {
        return $this->orderDao->getOrderListForExport($request);
    }

    /**
     * merge shop, terminal, staff and order info
     *
     * @param  Object $order
     * @param  Object $orderInfo
     * @param  Integer $shopId
     * @return Object
     */
    public function mergeOrderDetailsInfo($order, $orderInfo, $shopId)
    {
        // check order info and add terminal_name,restaurant_name and total to order
        if (! empty($orderInfo) && count($orderInfo) > 0) {
            $order->terminal_name = $orderInfo[0]->terminal_name;
            $order->restaurant_name = $orderInfo[0]->restaurant_name;
            $order->total = $orderInfo[0]->total;
        }

        // check shop id and add shop id to order
        if (! empty($shopId) && count($shopId) > 0) {
            $order->shop_id = $shopId[0];
        }

        return $order;
    }

    /**
     * Get Order Details from table
     *
     * @return Object $orderDetails
     */
    public function getOrderDetails($request)
    {
        return $this->orderDao->getOrderDetails($request);
    }

    /**
     * Accept order by cashier
     *
     * @return Boolean
     */
    public function acceptOrderByCashier($request)
    {
        $checkOrderStatus = $this->getCurrentOrderStatus($request->id);
        if ($checkOrderStatus->order_status == config('constants.ORDER_CREATE')) {
            return $this->orderDao->acceptOrderByCashier($request);
        }
        return false;
    }

    /**
     * Invoice order by cashier
     *
     * @return Boolean
     */
    public function invoiceOrderByCashier($request)
    {
        //check order status , if it is not order confirm condition return false
        $checkOrderStatus = $this->getCurrentOrderStatus($request->id);
        if ($checkOrderStatus->order_status == config('constants.ORDER_CONFIRM')) {
            return $this->orderDao->invoiceOrderByCashier($request);
        }
        return false;
    }

    /**
     * Get Order Pending List from storage
     *
     * @return Object Order Pending List
     */
    public function getPendingList($request)
    {
        return $this->orderDao->getPendingList($request);
    }

    /**
     * Get Order Invoice Recent List from storage
     *
     * @return Object OrderList
     */
    public function getInvoiceRecentList($request)
    {
        return $this->orderDao->getInvoiceRecentList($request);
    }

    /**
     * Get order invoice details
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $invoiceDetails
     */
    public function getOrderInvoiceDetails($request)
    {
        return $this->orderDao->getOrderInvoiceDetails($request);
    }

    /**
     * Get order list at kitchen
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $orderList
     */
    public function getOrderKitchenList($request)
    {
        return $this->orderDao->getOrderKitchenList($request);
    }

    /**
     * Confirm order at kitchen
     *
     * @param \Illuminate\Http\Request $request
     * @return Boolean
     */
    public function confirmOrderByKitchen($request)
    {
        // check order status , if it is not order accept condition return false
        $checkOrderStatus = $this->getCurrentOrderStatus($request->id);
        if ($checkOrderStatus->order_status == config('constants.ORDER_ACCEPT')) {
            return $this->orderDao->confirmOrderByKitchen($request);
        }

        return false;
    }

    /**
     * Get current order status search by id
     *
     * @param  Integer $id
     * @return Object
     */
    public function getCurrentOrderStatus($id)
    {
        return $this->orderDao->getCurrentOrderStatus($id);
    }

    /**
     * Get order restaurant table list at kitchen
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object
     */
    public function getOrderKitchenRestaurantTableList($request)
    {
        return $this->orderDao->getOrderKitchenRestaurantTableList($request);
    }

    /**
     * Insert order info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @return Integer $order_id
     */
    public function insertOrder($request)
    {
        $insertOrder = $this->orderDao->insertOrder($request);
        if (is_numeric($insertOrder)) {
            return $this->orderDetailsDao->insertOrderDetails($insertOrder, $request);
        }
        return false;
    }

    /**
     * Check order exist by order id
     *
     * @param  Integer $order_id
     * @return Boolean
     */
    public function checkOrderIDExists($order_id)
    {
        return $this->orderDao->checkOrderIDExists($order_id);
    }

    /**
    * Confirm Order Invoice Details
    *
    * @param  App\Http\Requests\Api\OrderInvoiceDetailsRequest $request
    * @return Object
    */
    public function confirmOrderInvoiceDetails($request)
    {
        return $this->orderDao->confirmOrderInvoiceDetails($request);
    }

    private function groupUpdateQtyPlus($request, $orderDetails)
    {
        foreach ($orderDetails as $orderInfo) {
            $this->orderDao->updateQtyPlus($request->shop_id, $orderInfo);
        }
    }
}
