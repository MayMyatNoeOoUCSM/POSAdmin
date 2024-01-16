<?php

namespace App\Http\Controllers;

use App\Contracts\Services\SaleDetailServiceInterface;
use App\Contracts\Services\SaleServiceInterface;
use App\Contracts\Services\ShopServiceInterface;
use App\Contracts\Services\TerminalServiceInterface;
use App\Exports\SaleDetailExport;
use App\Exports\SalesExport;
use App\Http\Requests\SaleRequest;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    private $salesDetailService;
    private $salesService;
    private $terminalService;
    private $shopService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\SaleServiceInterface $salesService
     * @param \App\Contracts\Services\SaleDetailServiceInterface $salesDetailService
     * @param \App\Contracts\Services\TerminalServiceInterface $terminalService
     * @param \App\Contracts\Services\ShopServiceInterface $shopService
     * @return void
     */
    public function __construct(SaleServiceInterface $salesService, SaleDetailServiceInterface $salesDetailService, TerminalServiceInterface $terminalService, ShopServiceInterface $shopService)
    {
        $this->salesService = $salesService;
        $this->salesDetailService = $salesDetailService;
        $this->terminalService = $terminalService;
        $this->shopService = $shopService;
    }

    /**
     *
     * Display a listing of the resource
     *
     * @param  \App\Http\Requests\SaleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(SaleRequest $request, Sale $sale)
    {
        // check download sale excel
        if (! is_null($request->download)) {
            return Excel::download(new SalesExport($this->salesService, $request, $sale), 'sales.xlsx');
        }
        // sale list and shop list
        $salesList = $this->salesService->getSaleList($request);
        $shopList = $this->shopService->getAllShopList();

        // check ajax request
        if ($request->ajax()) {
            Log::info('it is ajax call');
            $response = [
                'salesList' => $salesList,
            ];
            Log::info(json_encode($response));
            return json_encode($response);
        }
        if (is_null($salesList)) {
            return back()->with('error_status', __('message.E0001'));
        }

        return view('sale.index', compact('salesList', 'shopList'));
    }

    /**
     * Show the form to cancel invoice
     *
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        // sale details list, sale info and shop id
        $saleDetailsList = $this->salesDetailService->getSalesDetailBySaleId($sale->id);
        $saleInfo = $this->salesService->getSaleInfoById($sale->id);
        $shopId = $this->terminalService->getShopId($sale->terminal_id);

        // merge sale,shop,terminal and staff info
        $sale = $this->salesService->mergeSaleDetailsInfo($sale, $saleInfo, $shopId);
        if (is_null($saleDetailsList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('sale.edit', compact('sale', 'saleDetailsList'));
    }

    /**
     * Show the form to cancel invoice
     *
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function download(Sale $sale)
    {
        // sale details list, sale info and shop id
        $saleDetailsList = $this->salesDetailService->getSalesDetailBySaleId($sale->id);
        $saleInfo = $this->salesService->getSaleInfoById($sale->id);
        $shopId = $this->terminalService->getShopId($sale->terminal_id);

        // merge sale,shop,terminal and staff info
        $sale = $this->salesService->mergeSaleDetailsInfo($sale, $saleInfo, $shopId);
        if (is_null($saleDetailsList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return Excel::download(new SaleDetailExport($sale, $saleDetailsList), 'saledetail.xlsx');
    }

    /**
     *
     * Update the specified resource(cancel invoice) in storage
     *
     * @param \App\Http\Requests\SaleRequest  $request
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function cancelInvoice(SaleRequest $request, Sale $sale)
    {
        // check invoice
        $invoiceExists = $this->salesService->invoiceExistsInSaleReturn($sale->invoice_number);
        if ($invoiceExists) {
            return back()->with('error_status', __('message.W0007'));
        }

        // sale details list, update (cancel invoice) and check return statement
        $saleDetailsList = $this->salesDetailService->getSalesDetailBySaleId($sale->id);
        $cancelInvoice = $this->salesService->cancelSaleInvoice($request, $saleDetailsList);
        if ($cancelInvoice) {
            return redirect()->route('sale')->with('success_status', __('message.I0004', ["object_name" => 'Sale Invoice']));
        }

        return back()->with('error_status', __('message.E0001'));
    }
}
