<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\ProductServiceInterface;
use App\Contracts\Services\SaleDetailServiceInterface;
use App\Contracts\Services\SaleServiceInterface;
use App\Contracts\Services\TerminalServiceInterface;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\SaleCreateRequest;
use App\Http\Requests\Api\SaleInvoiceDetailsRequest;
use App\Http\Requests\Api\SaleInvoiceRequest;
use App\Http\Requests\Api\SaleRemoveRequest;
use App\Http\Requests\Api\SaleUpdateRequest;
use App\Http\Resources\SaleConfirmInvoiceDetailsResource;
use App\Http\Resources\SaleInvoiceDetailsResource;
use App\Http\Resources\SaleInvoiceResource;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends ApiController
{
    private $saleService;
    private $saleDetailsService;
    private $productService;
    private $terminalService;
    
    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\SaleServiceInterface $saleService
     * @param \App\Contracts\Services\SaleDetailServiceInterface $saleDetailsService
     * @param \App\Contracts\Services\ProductServiceInterface $productService
     * @param \App\Contracts\Services\TerminalServiceInterface $terminalService
     *
     * @return void
     */
    public function __construct(SaleServiceInterface $saleService, SaleDetailServiceInterface $saleDetailsService, ProductServiceInterface $productService, TerminalServiceInterface $terminalService)
    {
        $this->saleService = $saleService;
        $this->saleDetailsService = $saleDetailsService;
        $this->productService = $productService;
        $this->terminalService = $terminalService;
    }

    /**
     * Sale Invoice
     *
     * @param  App\Http\Requests\Api\SaleInvoiceRequest $request
     * @return Json
     */
    public function invoice(SaleInvoiceRequest $request)
    {
        $checkSaleIDExists = $this->saleService->checkSaleIDExists($request->id);
        if (! $checkSaleIDExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sale staff can\'t invoice process, because sale id is no exist.',
            ]);
        }

        $tmpTotal = $checkSaleIDExists->amount+$checkSaleIDExists->amount_tax;
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

        $saleInvoice = $this->saleService->invoiceSale($request);
        if ($saleInvoice) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sale staff make sale invoice.',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Sale staff can\'t make sale invoice.',
        ]);
    }

    /**
     * Recent sale invoice list
     *
     * @param  Illuminate\Http\Request $request
     * @return Collection $invoiceRecentList
     */
    public function invoiceRecentList(Request $request)
    {
        $invoiceRecentList = $this->saleService->getInvoiceRecentList($request);
        return SaleInvoiceResource::collection($invoiceRecentList)->additional(
            ['status'=>'success',
                'message'=>'Successfully retrieved sale recent invoice list.']
        );
    }

    /**
     * Pending sale invoice list
     *
     * @param  Illuminate\Http\Request $request
     * @return Collection $invoicePendingList
     */
    public function invoicePendingList(Request $request)
    {
        $invoicePendingList = $this->saleService->getInvoicePendingList($request);
        return SaleInvoiceResource::collection($invoicePendingList)->additional(
            ['status'=>'success',
                'message'=>'Successfully retrieved sale pending invoice list.']
        );
    }
    
    /**
     * Sale Invoice Details
     *
     * @param  App\Http\Requests\Api\SaleInvoiceDetailsRequest $request
     * @return Resource or Json
     */
    public function invoiceDetails(SaleInvoiceDetailsRequest $request)
    {
        $invoiceDetails = $this->saleService->getSaleInvoiceDetails($request);
        if ($invoiceDetails) {
            return new SaleInvoiceDetailsResource($invoiceDetails);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Invoice number does not exist.',
        ]);
    }

    /**
     * Sale Create
     *
     * @param  App\Http\Requests\Api\SaleCreateRequest $request
     * @return Json
     */
    public function saleCreate(SaleCreateRequest $request)
    {
        $terminal = $this->terminalService->getShopId($request->terminal_id);
        // return array
        if (count($terminal) == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sale terminal does not exist.',
            ]);
        }
        $product = $this->productService->checkProductArrayExists($request->data['product_id']);
        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sale product does not exist.',
            ]);
        }
        $sale = $this->saleService->insertSale($request);
        if (is_numeric($sale)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sale staff make sale.',
                'sale_id'=> $sale
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Sale staff can\'t make sale.',
        ]);
    }

    /**
     * Sale Update
     *
     * @param  App\Http\Requests\Api\SaleUpdateRequest $request
     * @return Json
     */
    public function saleUpdate(SaleUpdateRequest $request)
    {
        $checkSaleIDExists = $this->saleService->checkSaleIDExists($request->sale_id);
        if (! $checkSaleIDExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sale staff can\'t update sale process, because sale id is no exist.',
            ]);
        }
        $product = $this->productService->checkProductArrayExists($request->data['product_id']);
        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sale product does not exist.',
            ]);
        }
        $saleUpdate = $this->saleDetailsService->insertSaleDetails($request->sale_id, $request);
        if (is_numeric($saleUpdate)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sale staff update sale process.',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Sale staff can\'t update sale process.',
        ]);
    }

    /**
     * Sale Remove
     *
     * @param  App\Http\Requests\Api\SaleRemoveRequest $request
     * @return Json
     */
    public function saleRemove(SaleRemoveRequest $request)
    {
        $checkSaleIDExists = $this->saleService->checkSaleIDExists($request->sale_id);
        if (! $checkSaleIDExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sale staff can\'t remove sale process, because sale id is no exist.',
            ]);
        }
        $saleRemove = $this->saleDetailsService->removeSaleDetails($request->sale_id, $request->product_id);
        if ($saleRemove) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sale staff remove sale process.',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Sale staff can\'t remove sale process.',
        ]);
    }

    /**
     * Sale Confirm Invoice
     *
     * @param  App\Http\Requests\Api\SaleInvoiceDetailsRequest $request
     * @return Json
     */
    public function saleConfirmInvoice(SaleInvoiceDetailsRequest $request)
    {
        $invoiceDetails = $this->saleService->confirmSaleInvoiceDetails($request);
        if ($invoiceDetails) {
            return new SaleConfirmInvoiceDetailsResource($invoiceDetails);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Invoice number does not exist.',
        ]);
    }
}
