<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\OrderDetailsServiceInterface;
use App\Contracts\Services\OrderServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Contracts\Services\RestaurantServiceInterface;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\OrderCreateRequest;
use App\Http\Requests\Api\OrderDetailsRequest;
use App\Http\Requests\Api\OrderInvoiceDetailsRequest;
use App\Http\Requests\Api\OrderInvoiceRequest;
use App\Http\Requests\Api\OrderUpdateRequest;
use App\Http\Resources\OrderConfirmInvoiceDetailsResource;
use App\Http\Resources\OrderInvoiceDetailsResource;
use App\Http\Resources\OrderInvoiceResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    private $orderService;
    private $orderDetailsService;
    private $restaurantService;
    private $productService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\OrderServiceInterface $orderService
     * @param \App\Contracts\Services\OrderDetailsServiceInterface $orderDetailsService
     * @param \App\Contracts\Services\RestaurantServiceInterface $restaurantService
     * @param \App\Contracts\Services\ProductServiceInterface $productService
     * @return void
     */
    public function __construct(OrderServiceInterface $orderService, OrderDetailsServiceInterface $orderDetailsService, RestaurantServiceInterface $restaurantService, ProductServiceInterface $productService)
    {
        $this->orderService = $orderService;
        $this->orderDetailsService = $orderDetailsService;
        $this->restaurantService = $restaurantService;
        $this->productService = $productService;
    }

    /**
     * Order List
     *
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function index(Request $request)
    {
        $orderList = $this->orderService->getOrderList($request);
        return OrderResource::collection($orderList)
            ->additional(['status'=>'success',
                'message'=>'Successfully retrieved order list.']);
    }

    /**
     * Order Details
     *
     * @param  App\Http\Requests\Api\OrderDetailsRequest $request
     * @return Collection or Json
     */
    public function details(OrderDetailsRequest $request)
    {
        $orderDetails = $this->orderService->getOrderDetails($request);
        if ($orderDetails) {
            return new OrderResource($orderDetails);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Order details does not exist.',
        ]);
    }

    /**
     * Order Invoice
     *
     * @param  App\Http\Requests\Api\OrderInvoiceRequest $request
     * @return Json
     */
    public function invoice(OrderInvoiceRequest $request)
    {
        $checkOrderIDExists = $this->orderService->checkOrderIDExists($request->id);
        if (! $checkOrderIDExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cashier staff can\'t invoice process, because order id is no exist.',
            ]);
        }
        $tmpTotal = $checkOrderIDExists->amount+$checkOrderIDExists->amount_tax;
        // check paid amount
        if ($tmpTotal > $request->paid_amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paid amount is not enough.',
            ]);
        }
        if ($request->paid_amount - $tmpTotal != $request->change_amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Change amount is not correct.',
            ]);
        }

        $invoiceOrder = $this->orderService->invoiceOrderByCashier($request);
        if ($invoiceOrder) {
            return response()->json([
                'status' => 'success',
                'message' => 'Cashier make order invoice.',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Cashier can\'t make order invoice.',
        ]);
    }

    /**
     * Invoice Recent List
     *
     * @param  Illuminate\Http\Request $request
     * @return Collection
     */
    public function invoiceRecentList(Request $request)
    {
        $invoiceRecentList = $this->orderService->getInvoiceRecentList($request);
        return OrderInvoiceResource::collection($invoiceRecentList)->additional(
            ['status'=>'success',
                'message'=>'Successfully retrieved order recent invoice list.']
        );
    }

    /**
     * Invoice Details
     *
     * @param  App\Http\Requests\Api\OrderInvoiceDetailsRequest $request
     * @return Resource or Json
     */
    public function invoiceDetails(OrderInvoiceDetailsRequest $request)
    {
        $invoiceDetails = $this->orderService->getOrderInvoiceDetails($request);
        if ($invoiceDetails) {
            return new OrderInvoiceDetailsResource($invoiceDetails);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Invoice number does not exist.',
        ]);
    }

    /**
     * Order Create
     *
     * @param  App\Http\Requests\Api\OrderCreateRequest $request
     * @return Json
     */
    public function orderCreate(OrderCreateRequest $request)
    {
        $restaurantTable = $this->restaurantService->checkTableAvailable($request->restaurant_table_id);
        if (! $restaurantTable) {
            return response()->json([
                'status' => 'error',
                'message' => 'Waiter can\'t make order, because restaurant table is no free or can\'t use.',
            ]);
        }

        $product = $this->productService->checkProductArrayExists($request->data['product_id']);
        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order product does not exist.',
            ]);
        }

        $ordercreate = $this->orderService->insertOrder($request);
        if (is_numeric($ordercreate)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Waiter make order.',
                'order_id'=> $ordercreate
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Waiter can\'t make order.',
        ]);
    }

    /**
     * Order Update
     *
     * @param  App\Http\Requests\Api\OrderUpdateRequest $request
     * @return Json
     */
    public function orderUpdate(OrderUpdateRequest $request)
    {
        $checkOrderIDExists = $this->orderService->checkOrderIDExists($request->order_id);
        if (! $checkOrderIDExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Waiter can\'t update order, because order id is no exist.',
            ]);
        }

        $product = $this->productService->checkProductArrayExists($request->data['product_id']);
        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order product does not exist.',
            ]);
        }

        $orderUpdate = $this->orderDetailsService->insertOrderDetails($request->order_id, $request);
        if ($orderUpdate) {
            return response()->json([
                'status' => 'success',
                'message' => 'Waiter update order.',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Waiter can\'t update order.',
        ]);
    }

    /**
    * Order Confirm Invoice
    *
    * @param  App\Http\Requests\Api\OrderInvoiceDetailsRequest $request
    * @return Json
    */
    public function orderConfirmInvoice(OrderInvoiceDetailsRequest $request)
    {
        $invoiceDetails = $this->orderService->confirmOrderInvoiceDetails($request);
        if ($invoiceDetails) {
            return new OrderConfirmInvoiceDetailsResource($invoiceDetails);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Invoice number does not exist.',
        ]);
    }
}
