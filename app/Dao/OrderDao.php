<?php

namespace App\Dao;

use App\Contracts\Dao\OrderDaoInterface;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\WarehouseShopProductRel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderDao implements OrderDaoInterface
{
    /**
     * Get pending order invoice count search by terminal id
     *
     * @param  Integer $terminalId
     * @return Integer $orderCount
     */
    public function getPendingOrderCount($terminalId)
    {
        $orderCount = Order::where('order_status', config('constants.INVOICE_PENDING'))->where('terminal_id', $terminalId)->count();
        return $orderCount;
    }

    /**
     * Get order list
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $orderList
     */
    public function getOrderList($request)
    {
        $orderList = Order::leftJoin('m_terminal as t', 't_order.terminal_id', '=', 't.id')
            ->leftJoin('m_shop as shop', 'shop.id', '=', 't.shop_id')
            ->leftJoin('m_restaurant_table as rt', 't_order.restaurant_table_id', '=', 'rt.id');
        // $orderList = $orderList->where('t_order.order_status', '!=', config('constants.INVOICE_PENDING'));
        if (! empty($request->search_invoice_no)) {
            $query = $orderList->where('t_order.invoice_number', 'like', '%' . $request->search_invoice_no . '%');
        }

        // order date from
        if (! empty($request->search_order_date_from)) {
            $query = $orderList->where('t_order.order_date', '>=', $request->search_order_date_from);
        }
        // order date to
        if (! empty($request->search_order_date_to)) {
            $query = $orderList->where('t_order.order_date', '<=', $request->search_order_date_to);
        }
        // invoice status
        if (! empty($request->search_order_status)) {
            $orderList = $orderList->where('t_order.order_status', $request->search_order_status);
        }
        // shop name
        if (! empty($request->search_shop_name)) {
            $orderList = $orderList->where('shop.name', 'like', '%' . $request->search_shop_name . '%');
        }

        // weekly, monthly and yearly
        if (empty($request->search_order_date_from) and empty($request->search_order_date_to)) {
            if (! empty($request->orderdateby)) {
                $dt_min = new \DateTime("last saturday");
                $dt_min->modify('+1 day');
                $dt_max = clone ($dt_min);
                $dt_max->modify('+6 days');
                if ($request->orderdateby == 'weekly') {
                    $query = $orderList->where('t_order.order_date', '>', $dt_min->format("Y-m-d"));
                    $query = $orderList->where('t_order.order_date', '<=', $dt_max->format("Y-m-d"));
                } elseif ($request->orderdateby == 'monthly') {
                    $query = $orderList->where('t_order.order_date', '>=', $dt_min->format("Y-m-01"));
                    $query = $orderList->where('t_order.order_date', '<=', $dt_max->format("Y-m-t"));
                } elseif ($request->orderdateby == 'yearly') {
                    $query = $orderList->where('t_order.order_date', '>=', $dt_min->format("Y-01-01"));
                    $query = $orderList->where('t_order.order_date', '<=', $dt_max->format("Y-12-t"));
                } else {
                    $query = $orderList->where('t_order.order_date', '=', date("Y-m-d"));
                }
            }
        }
        // sorting list order
        if ($request->sorting_column) {
            $orderList = $orderList->orderBy($request->sorting_column, $request->sorting_order);
        }

        $orderList = $orderList->select(DB::raw('t_order.id,t_order.order_status,t_order.order_date,t_order.invoice_number,shop.name as shop_name,t.name as terminal_name, t_order.amount, t_order.amount, t_order.amount_tax, rt.name as restaurant_name, t_order.remark as remark, (t_order.amount+t_order.amount_tax)as total'));
        $orderList = $orderList->paginate($request->custom_pg_size == "" ? config('constants.ORDER_PAGINATION') : $request->custom_pg_size);
        return $orderList;
    }

    /**
     * Get total order quantity search by order id
     *
     * @param  Integer $order_id
     * @return Object
     */
    public function getTotalOrderQtyByOrderId($order_id)
    {
        $query = Order::leftJoin('t_order_details as detail', 't_order.id', '=', 'detail.order_id')
            ->where('detail.order_id', '=', $order_id)
            ->groupBy('detail.order_id')
            ->select(DB::raw('detail.order_id,SUM(detail.quantity) as total_order_qty'))
            ->get();
        if (! empty($query)) {
            $retValue = $query->pluck('total_order_qty')->first();
            return $retValue;
        }
    }

    /**
     * Get order lists that have been confirmed invoice
     *
     * @param  \App\Http\Requests\OrderRequest $request
     * @return Object $saleList
     */
    public function getConfirmedOrderList($request)
    {
        $orderList = Order::leftJoin('m_terminal as t', 't_order.terminal_id', '=', 't.id')
            ->leftJoin('m_shop as shop', 'shop.id', '=', 't.shop_id')
            ->leftJoin('m_restaurant_table as rt', 't_order.restaurant_table_id', '=', 'rt.id')
            ->where('t_order.order_status', '=', config('constants.INVOICE_CONFIRM'));

        // invoice number
        if (! empty($request->search_invoice_no)) {
            $orderList = $orderList->where('t_order.invoice_number', 'like', '%' . $request->search_invoice_no . '%');
        }
        // sale date from
        if (! empty($request->search_order_date_from)) {
            $orderList = $orderList->where('t_order.order_date', '>=', $request->search_order_date_from);
        }
        // sale date to
        if (! empty($request->search_order_date_to)) {
            $orderList = $orderList->where('t_order.order_date', '<=', $request->search_order_date_to);
        }
        // staff name
        if (! empty($request->search_restaurant_name)) {
            $orderList = $orderList->where('rt.name', 'like', '%' . $request->search_restaurant_name . '%');
        }

        $orderList = $orderList->select(DB::raw('t_order.id,t_order.order_status,t_order.order_date,t_order.invoice_number, t.name as terminal_name, t_order.amount, t_order.amount, t_order.amount_tax, rt.name as restaurant_name, t_order.remark as remark, (t_order.amount+t_order.amount_tax)as total,shop.name as shop_name'));
        $orderList = $orderList->paginate($request->custom_pg_size == "" ? config('constants.ORDER_PAGINATION') : $request->custom_pg_size);

        return $orderList;
    }

    /**
     * Get order info search by id
     *
     * @param  Integer $order_id
     * @return Object $orderInfo
     */
    public function getOrderInfoById($order_id)
    {
        $orderInfo = Order::leftJoin('m_terminal as t', 't_order.terminal_id', '=', 't.id')
            ->leftJoin('m_restaurant_table as rt', 't_order.restaurant_table_id', '=', 'rt.id')
            ->leftJoin('m_shop as shop', 't.shop_id', '=', 'shop.id');
        if (! empty($order_id)) {
            $orderInfo = $orderInfo->where('t_order.id', '=', $order_id);
        }

        $orderInfo = $orderInfo->select(DB::raw('t_order.id,t_order.order_date,t_order.invoice_number, t.name as terminal_name, t_order.amount, t_order.amount, t_order.amount_tax, rt.name as restaurant_name, t_order.remark as remark, (t_order.amount+t_order.amount_tax)as total,shop.name as shop_name'))->get();
        return $orderInfo;
    }

    /**
     * Get Order Details from table
     *
     * @return Object $orderDetails
     */
    public function getOrderDetails($request)
    {
        $orderDetails = Order::where('id', $request->id)->first();
        return $orderDetails;
    }

    /**
     * Accept order by cashier
     *
     * @return Object $orderDetails or Boolean
     */
    public function acceptOrderByCashier($request)
    {
        $orderDetails = Order::where('id', $request->id)->update(['order_status' => config('constants.ORDER_ACCEPT')]);
        return $orderDetails;
    }

    /**
     * Invoice order by cashier
     *
     * @return Object $orderDetails or Boolean
     */
    public function invoiceOrderByCashier($request)
    {
        $orderDetails = Order::where('id', $request->id)
            ->update([
                'order_status' => config('constants.ORDER_INVOICE'),
                // 'amount' => $request->amount,
                // 'amount_tax' => $request->amount_tax,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->change_amount,
            ]);
        \App\Models\Restaurant::where('id', Order::find($request->id)->restaurant_table_id)
            ->update(['available_status' => config('constants.RESTAURANT_TABLE_FREE')]);

        return $orderDetails;
    }

    /**
     * Get Order Pending List from storage
     *
     * @return Object Order Pending List
     */
    public function getPendingList($request)
    {
        $orderList = Order::where('order_status', config('constants.ORDER_CREATE'))
            ->orderBy('id', 'DESC')
            ->paginate($request->custom_pg_size == "" ?
                config('constants.ORDER_PAGINATION') : $request->custom_pg_size);
        return $orderList;
    }

    /**
     * Get Order Invoice Recent List from storage
     *
     * @return Object OrderList
     */
    public function getInvoiceRecentList($request)
    {
        $orderList = Order::where('order_status', config('constants.ORDER_INVOICE'))
            ->orderBy('id', 'DESC')
            ->where(function ($q) use ($request) {
                if (! empty($request->invoice_number)) {
                    $q->where("invoice_number", "like", "%" . $request->invoice_number . "%");
                }
            })
            ->orderBy('update_datetime', 'DESC')
            ->limit(10)
            ->get();
        return $orderList;
    }

    /**
     * Get Order Invoice Details from table
     *
     * @return Object $invoiceDetails
     */
    public function getOrderInvoiceDetails($request)
    {
        $invoiceDetails = Order::where('invoice_number', $request->invoice_number)
            ->where('order_status', config('constants.ORDER_INVOICE'))
            ->first();
        return $invoiceDetails;
    }

    /**
     * Get order list at kitchen
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $orderList
     */
    public function getOrderKitchenList($request)
    {
        $orderList = Order::where('order_status', config('constants.ORDER_ACCEPT'))
            ->orderBy('id', 'DESC')
            ->paginate($request->custom_pg_size == "" ?
                config('constants.ORDER_PAGINATION') : $request->custom_pg_size);
        return $orderList;
    }

    /**
     * Confirm order at kitchen
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $orderDetails
     */
    public function confirmOrderByKitchen($request)
    {
        $orderDetails = Order::where('id', $request->id)
            ->update([
                'order_status' => config('constants.ORDER_CONFIRM'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ]);
        return $orderDetails;
    }

    /**
     * Get current order status search by id
     *
     * @param  Integer $id
     * @return Object
     */
    public function getCurrentOrderStatus($id)
    {
        $orderDetails = Order::where('id', $id)->first();
        return $orderDetails;
    }

    /**
     * Get order restaurant table list at kitchen
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $orderList
     */
    public function getOrderKitchenRestaurantTableList($request)
    {
        $orderList = Order::where('order_status', config('constants.ORDER_ACCEPT'))
            ->orWhere('order_status', config('constants.ORDER_CONFIRM'))
            ->orderBy('id', 'DESC')
            ->paginate($request->custom_pg_size == "" ?
                config('constants.ORDER_PAGINATION') : $request->custom_pg_size);
        return $orderList;
    }

    /**
     * Insert order info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @return Integer $order_id
     */
    public function insertOrder($request)
    {
        $shopid = str_pad(Auth::user()->shop_id, 3, '0', STR_PAD_LEFT);
        $restaurant_table_id = str_pad($request->restaurant_table_id, 3, '0', STR_PAD_LEFT);
        $lastvalue = DB::select("select last_value from t_order_id_seq");
        $lastorderid = $lastvalue[0]->last_value;
        // $lastorder  = Order::select('id')->orderBy('id', 'DESC')->first();
        // if ($lastorder) {
        //     $lastorderid  = Order::select('id')->orderBy('id', 'DESC')->first()->id;
        // } else {
        //     $lastorderid = 0;
        // }
        $lastorderid = str_pad($lastorderid+1, 4, '0', STR_PAD_LEFT);
        $invoice_number = $shopid . $restaurant_table_id . date("ymd").$lastorderid;

        $order = new Order;
        $order->invoice_number = $invoice_number;
        $order->terminal_id = 1;
        $order->shop_id = Auth::user()->shop_id;
        $order->restaurant_table_id = $request->restaurant_table_id;
        $order->order_date = date('Y-m-d');
        $order->order_status = config('constants.ORDER_CREATE');
        $order->create_user_id = Auth::user()->id;
        $order->update_user_id = Auth::user()->id;
        $order->create_datetime = date('Y-m-d H:i:s');
        $order->update_datetime = date('Y-m-d H:i:s');
        $order->amount = 0;
        $order->amount_tax = 0;
        $order->paid_amount = 0;
        $order->change_amount = 0;
        $order->save();

        \App\Models\Restaurant::where('id', $request->restaurant_table_id)
            ->update(['available_status' => config('constants.RESTAURANT_TABLE_ORDER')]);
        // $table  = \App\Models\Restaurant::find($request->restaurant_table_id);
        // $table->available_status = config('constants.RESTAURANT_TABLE_ORDER');
        // $table->save();

        return $order->id;
    }

    /**
     * Update product quantity (warehouse shop product table) in storage by adding quantity
     *
     * @param  Integer $shop_id
     * @param  Object $request
     * @return Object $warehouseShopProduct
     */
    public function updateQtyPlus($shop_id, $request)
    {
        $warehouseShopProduct = WarehouseShopProductRel::where([
            ['shop_id', $shop_id],
            ['product_id', $request->product_id],
        ])->first();
        if (! is_null($warehouseShopProduct)) {
            WarehouseShopProductRel::where([
                ['shop_id', $shop_id],
                ['product_id', $request->product_id],
            ])->update([
                'quantity' => $warehouseShopProduct->quantity + $request->qty,
            ]);
        }
        return $warehouseShopProduct;
    }

    /**
     * Update product quantity (warehouse shop product table) in storage by reducing quantity
     *
     * @param Object $request
     * @return Object $warehouseShopProduct
     */
    public function updateQtyMinus($request)
    {
        $warehouseShopProduct = WarehouseShopProductRel::where([
            ['shop_id', $request->shop_id],
            ['product_id', $request->product_id],
        ])->first();
        if (! is_null($warehouseShopProduct)) {
            WarehouseShopProductRel::where([

                ['shop_id', $request->shop_id],
                ['product_id', $request->product_id],
            ])->update([
                'quantity' => $warehouseShopProduct->quantity - $request->qty,

            ]);
        }
        return $warehouseShopProduct;
    }

    /**
     * Cancel Order
     *
     * @param  Object $request
     * @return Boolean
     */
    public function cancelOrder($request)
    {
        // update order
        $order = Order::where('id', $request->id)
            ->update([
                'order_status' => config('constants.INVOICE_CANCELLED'),
                'reason' => $request->cancellation_reason,
                'update_user_id' => auth()->user()->id,
                'update_datetime' => Date('Y-m-d H:i:s'),
            ]);
        // get shop resource
        $shop = Order::select('m_shop.id', 'm_shop.name')
            ->join("m_terminal", "m_terminal.id", "=", "t_order.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->where('t_order.id', $request->id)
            ->first();
        // get order details list and loop to update product quantity by adding
        $orderDetails = OrderDetail::where('order_id', '=', $request->id)->get();
        foreach ($orderDetails as $key => $value) {
            \DB::table('t_warehouse_shop_product')->where('product_id', $value->product_id)
                ->where('shop_id', $shop->id)
                ->increment('quantity', $value->quantity);
        }
        return $order;
    }

    /**
     * Get total order amount for today
     *
     * @return Object or boolean
     */
    public function getTodayOrderTotal()
    {
        $query = Order::select(DB::raw("sum(amount)"), "order_date")
            ->where('order_date', '=', Date("Y-m-d"))
            ->groupBy('order_date')
            ->first();
        if (! empty($query)) {
            return $query;
        }
        return false;
    }

    /**
     * Get total order amount for yesterday
     *
     * @return Object or boolean
     */
    public function getYesterdayOrderTotal()
    {
        $query = Order::select(DB::raw("sum(amount)"), "order_date")
            ->where('order_date', '=', Date("Y-m-d", strtotime('-1 day')))
            ->groupBy('order_date')
            ->first();
        if (! empty($query)) {
            return $query;
        }
        return false;
    }

    /**
     * Get total order amount for current month
     *
     * @return Object or boolean
     */
    public function getMonthOrderTotal()
    {
        $query = Order::select(DB::raw("sum(amount)"))
            ->where('order_date', '>=', Date("Y-m-01"))
            ->where('order_date', '<=', Date("Y-m-t"))
            ->first();
        if (! empty($query)) {
            return $query;
        }
        return false;
    }

    /**
     * Get total order amount for current year
     *
     * @return Object or boolean
     */
    public function getYearOrderTotal()
    {
        $query = Order::select(DB::raw("sum(amount)"))
            ->where('order_date', '>=', Date("Y-01-01"))
            ->where('order_date', '<=', Date("Y-12-t"))
            ->first();
        if (! empty($query)) {
            return $query;
        }
        return false;
    }

    /**
     * Get order date, total order amount and shop name for today
     *
     * @return Object or boolean
     */
    public function getTodayOrderShops()
    {
        $query = Order::select(DB::raw("sum(t_order.amount)"), "t_order.order_date", "m_shop.name")
            ->join("m_terminal", "m_terminal.id", "=", "t_order.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->where('t_order.order_date', '=', Date("Y-m-d"))
            ->groupBy('t_order.order_date', "m_shop.name")
            ->get();
        if (! empty($query)) {
            return $query->toArray();
        }
        return false;
    }

    /**
     * Get order list search by order dates
     *
     * @param Array $order_date
     * @return Array or boolean
     */
    public function getOrderDataByDates($order_date)
    {
        $query = Order::select(\DB::raw("sum(amount)"), 'order_date')
            ->whereIn("order_date", $order_date)
            ->groupBy("order_date")
            ->get();
        if (! empty($query)) {
            return $query->toArray();
        }
        return false;
    }

    /**
     * Get monthly order info search by order from date and order to date
     *
     * @param Date $from_date
     * @param Date $to_date
     * @return Array or boolean
     */
    public function getMonthlyOrder($from_date, $to_date)
    {
        $query = Order::select(DB::raw("sum(amount)"), DB::raw("date_part('month', order_date) AS Order_Month"))
            ->whereDate("order_date", ">=", $from_date)
            ->whereDate("order_date", "<=", $to_date)
            ->groupBy(DB::raw("date_part('month', order_date)"))
            ->get();
        if (! empty($query)) {
            return $query->toArray();
        }
        return false;
    }

    /**
     * Get all total order amount
     *
     * @return Integer or boolean
     */
    public function getOrderTotal()
    {
        $query = Order::select(DB::raw("sum(amount)"))
            ->first();
        if (! empty($query)) {
            return $query;
        }
        return false;
    }

    /**
     * Get order list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $orderList
     */
    public function getOrderListForExport($request)
    {
        $orderList = Order::leftJoin('m_terminal as t', 't_order.terminal_id', '=', 't.id')
            ->leftJoin('m_shop as shop', 'shop.id', '=', 't.shop_id')
            ->leftJoin('m_restaurant_table as rt', 't_order.restaurant_table_id', '=', 'rt.id');
        // $orderList = $orderList->where('t_order.invoice_status', '!=', config('constants.INVOICE_PENDING'));
        if (! empty($request->search_invoice_no)) {
            $query = $orderList->where('t_order.invoice_number', 'like', '%' . $request->search_invoice_no . '%');
        }

        // from date
        if (! empty($request->search_order_date_from)) {
            $query = $orderList->where('t_order.order_date', '>=', $request->search_order_date_from);
        }
        // to date
        if (! empty($request->search_order_date_to)) {
            $query = $orderList->where('t_order.order_date', '<', $request->search_order_date_to);
        }
        // invoice status
        if (! empty($request->search_invoice_status)) {
            $orderList = $orderList->where('t_order.order_status', $request->search_invoice_status);
        }
        // shop name
        if (! empty($request->search_shop_name)) {
            $orderList = $orderList->where('shop.name', 'like', '%' . $request->search_shop_name . '%');
        }
        // weekly, monthly, yearly
        if (empty($request->search_order_date_from) and empty($request->search_order_date_to)) {
            if (! empty($request->orderdateby)) {
                $dt_min = new \DateTime("last saturday"); // Edit
                $dt_min->modify('+1 day'); // Edit
                $dt_max = clone ($dt_min);
                $dt_max->modify('+6 days');
                if ($request->orderdateby == 'weekly') {
                    $query = $orderList->where('t_order.order_date', '>', $dt_min->format("Y-m-d"));
                    $query = $orderList->where('t_order.order_date', '<=', $dt_max->format("Y-m-d"));
                } elseif ($request->orderdateby == 'monthly') {
                    $query = $orderList->where('t_order.order_date', '>=', $dt_min->format("Y-m-01"));
                    $query = $orderList->where('t_order.order_date', '<=', $dt_max->format("Y-m-t"));
                } elseif ($request->orderdateby == 'yearly') {
                    $query = $orderList->where('t_order.order_date', '>=', $dt_min->format("Y-01-01"));
                    $query = $orderList->where('t_order.order_date', '<=', $dt_max->format("Y-12-t"));
                } else {
                    $query = $orderList->where('t_order.order_date', '=', date("Y-m-d"));
                }
            }
        }

        // sorting order list
        if ($request->sorting_column) {
            $orderList = $orderList->orderBy($request->sorting_column, $request->sorting_order);
        }

        $orderList = $orderList->select(DB::raw("t_order.order_date,t_order.invoice_number,shop.name as shop_name,t.name as terminal_name, t_order.amount, t_order.amount, t_order.amount_tax, rt.name as restaurant_name, t_order.remark as remark, (t_order.amount+t_order.amount_tax)as total,CASE
                WHEN t_order.order_status = 1 THEN 'Order Create (Waiter)'
                WHEN t_order.order_status = 2 THEN 'Order Confirm (Kitchen)'
                ELSE 'Order Invoice (Cashier)'
            END"));
        $orderList = $orderList->get();
        return $orderList;
    }

    /**
     * Check order exist by order id
     *
     * @param  Integer $order_id
     * @return Boolean
     */
    public function checkOrderIDExists($order_id)
    {
        $order = Order::FindOrFail($order_id);
        return $order;
    }

    /**
    * Confirm Order Invoice Details
    *
    * @param  App\Http\Requests\Api\OrderInvoiceDetailsRequest $request
    * @return Object
    */
    public function confirmOrderInvoiceDetails($request)
    {
        $invoiceDetails = Order::where('invoice_number', $request->invoice_number)
            ->first();
        if ($invoiceDetails->order_status ==  config('constants.ORDER_CONFIRM')) {
            $totalAmount= 0;
            $taxAmount  = 0;
            foreach ($invoiceDetails->details as $key => $value) {
                if ($value->order_details_status == config('constants.ORDER_DETAILS_CONFIRM')) {
                    $totalAmount += $value->quantity * $value->price;
                }
            }
            $taxAmount = $totalAmount*5/100;
            $invoiceDetails->amount = $totalAmount;
            $invoiceDetails->amount_tax = $taxAmount;
            $invoiceDetails->save();

            return $invoiceDetails;
        }
        return $invoiceDetails;
    }
}
