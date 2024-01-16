<?php

namespace App\Http\Controllers;

use App\Contracts\Services\OrderDetailsServiceInterface;
use App\Contracts\Services\OrderServiceInterface;
use App\Contracts\Services\RestaurantServiceInterface;
use App\Contracts\Services\ShopServiceInterface;
use App\Contracts\Services\TerminalServiceInterface;
use App\Exports\OrderExport;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    private $orderDetailsService;
    private $orderService;
    private $terminalService;
    private $shopService;
    private $restaurantService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\OrderServiceInterface $orderService
     * @param \App\Contracts\Services\OrderDetailsServiceInterface $orderDetailsService
     * @param \App\Contracts\Services\TerminalServiceInterface $terminalService
     * @param \App\Contracts\Services\ShopServiceInterface $shopService
     * @param \App\Contracts\Services\RestaurantServiceInterface $restaurantService
     * @return void
     */
    public function __construct(OrderServiceInterface $orderService, OrderDetailsServiceInterface $orderDetailsService, TerminalServiceInterface $terminalService, ShopServiceInterface $shopService, RestaurantServiceInterface $restaurantService)
    {
        $this->orderService = $orderService;
        $this->orderDetailsService = $orderDetailsService;
        $this->terminalService = $terminalService;
        $this->shopService = $shopService;
        $this->restaurantService = $restaurantService;
    }

    /**
     *
     * Display a listing of the resource
     *
     * @param  \App\Http\Requests\OrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(OrderRequest $request)
    {
        // check download order excel
        if (! is_null($request->download)) {
            return Excel::download(new OrderExport($this->orderService, $request), 'orders.xlsx');
        }
        // order list and shop list
        $orderList = $this->orderService->getOrderList($request);
        $shopList = $this->shopService->getAllShopList();

        // check ajax request
        if ($request->ajax()) {
            Log::info('it is ajax call');
            $response = [
                'orderList' => $orderList,
            ];
            Log::info(json_encode($response));
            return json_encode($response);
        }
        if (is_null($orderList)) {
            return back()->with('error_status', __('message.E0001'));
        }

        return view('order.index', compact('orderList', 'shopList'));
    }

    /**
     * Show the form to cancel invoice
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        // order details list, order info and shop id
        $orderDetailsList = $this->orderDetailsService->getOrderDetailsByOrderId($order->id);
        $orderInfo = $this->orderService->getOrderInfoById($order->id);
        $shopId = $this->terminalService->getShopId($order->terminal_id);

        // merge order,shop,terminal and staff info
        $order = $this->orderService->mergeOrderDetailsInfo($order, $orderInfo, $shopId);
        if (is_null($orderDetailsList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('order.edit', compact('order', 'orderDetailsList'));
    }

    /**
     *
     * Update the specified resource(cancel invoice) in storage
     *
     * @param \App\Http\Requests\OrderRequest  $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function cancelOrder(OrderRequest $request, Order $order)
    {
        // order details list, update (cancel order) and check return statement
        $orderDetailsList = $this->orderDetailsService->getOrderDetailsByOrderId($order->id);
        $cancelOrder = $this->orderService->cancelOrder($request, $orderDetailsList);
        if ($cancelOrder) {
            return redirect()->route('order')->with('success_status', __('message.I0004', ["object_name" => 'Order Invoice']));
        }

        return back()->with('error_status', __('message.E0001'));
    }
}
