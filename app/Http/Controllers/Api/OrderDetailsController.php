<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\OrderDetailsServiceInterface;
use App\Contracts\Services\OrderServiceInterface;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\OrderDetailsRequest;
use App\Http\Resources\OrderDetailsResource;
use App\Models\OrderDetails;
use Illuminate\Http\Request;

class OrderDetailsController extends ApiController
{
    private $orderService;
    private $orderDetailsService;
   
    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\OrderServiceInterface $orderService
     * @param \App\Contracts\Services\OrderDetailServiceInterface $orderDetailsService
     *
     * @return void
     */
    public function __construct(OrderDetailsServiceInterface $orderDetailsService, OrderServiceInterface $orderService)
    {
        $this->orderDetailsService = $orderDetailsService;
        $this->orderService = $orderService;
    }

    /**
     * Order Details List At Kitchen
     *
     * @param  Illuminate\Http\Requests\Request $request
     * @return Collection
     */
    public function orderDetailsListAtKitchen(Request $request)
    {
        $orderList = $this->orderDetailsService->getOrderDetailsList($request);
        return OrderDetailsResource::collection($orderList)
            ->additional(['status'=>'success',
                'message'=>'Successfully retrieved order details list.']);
    }

    /**
     * Order Details Confirm At Kitchen
     * @param  App\Http\Requests\Api\OrderDetailsRequest $request
     * @return Json
     */
    public function orderDetailsConfirmAtKitchen(OrderDetailsRequest $request)
    {
        $confirmOrder = $this->orderDetailsService->confirmOrderByKitchen($request);
        if ($confirmOrder) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kitchen make confirm order.',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Kitchen can\'t make confirm order.',
        ]);
    }

    /**
     * Order Confirm Items
     * @param  App\Http\Requests\Api\OrderDetailsRequest $request
     * @return Json or Collection
     */
    public function orderConfirmItems(OrderDetailsRequest $request)
    {
        $checkOrderIDExists = $this->orderService->checkOrderIDExists($request->id);
        if (! $checkOrderIDExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cashier can\'t call confirm order items, because order id is no exist.',
            ]);
        }
        $confirmOrderItems = $this->orderDetailsService->getOrderConfirmItems($request);
        return OrderDetailsResource::collection($confirmOrderItems)
            ->additional(['status'=>'success',
                'message'=>'Successfully retrieved confirm order items list.']);
    }
}
