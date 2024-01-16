<?php
namespace App\Http\Controllers;

use App\Contracts\Services\DamageDetailServiceInterface;
use App\Contracts\Services\DamageLossServiceInterface;
use App\Contracts\Services\ReturnDetailServiceInterface;
use App\Contracts\Services\SaleDetailServiceInterface;
use App\Contracts\Services\SaleReturnServiceInterface;
use App\Contracts\Services\SaleServiceInterface;
use App\Contracts\Services\TerminalServiceInterface;
use App\Http\Requests\DamageLossRequest;
use App\Http\Requests\SaleReturnInvoiceListRequest;
use App\Http\Requests\SaleReturnListRequest;
use App\Http\Requests\SaleReturnRequest;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaleReturnController extends Controller
{
    private $salesService;
    private $salesDetailService;
    private $returnService;
    private $retDetailService;
    private $damageService;
    private $damageDetailService;
    private $terminalService;
    /**
       * Create a new controller instance.
       *
       * @param \App\Contracts\Services\SaleServiceInterface $salesService
       * @param \App\Contracts\Services\SaleDetailServiceInterface $salesDetailService
       * @param \App\Contracts\Services\SaleReturnServiceInterface $returnService
       * @param \App\Contracts\Services\ReturnDetailServiceInterface $retDetailService
       * @param \App\Contracts\Services\DamageLossServiceInterface $damageService
       * @param \App\Contracts\Services\DamageDetailServiceInterface $damageDetailService
       * @param \App\Contracts\Services\TerminalServiceInterface $terminalService
       * @return void
    */
    public function __construct(SaleServiceInterface $salesService, SaleDetailServiceInterface $salesDetailService, SaleReturnServiceInterface $returnService, ReturnDetailServiceInterface $retDetailService, DamageLossServiceInterface $damageService, DamageDetailServiceInterface $damageDetailService, TerminalServiceInterface $terminalService)
    {
        $this->salesService = $salesService;
        $this->salesDetailService = $salesDetailService;
        $this->returnService = $returnService;
        $this->retDetailService = $retDetailService;
        $this->damageService = $damageService;
        $this->damageDetailService = $damageDetailService;
        $this->terminalService = $terminalService;
    }

    /**
    *
    * Display a listing of the resource (sale)
    *
    * @param  \App\Http\Requests\SaleReturnListRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function index(SaleReturnListRequest $request)
    {
            
        // sale return list and check return statement
        $saleReturnList = $this->returnService->getSaleReturnList($request);
        if (is_null($saleReturnList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('return.index', compact('saleReturnList'));
    }

   
    /**
     * Display a listing of the resource (sale invoice)
     *
     * @param  \App\Http\Requests\SaleReturnInvoiceListRequest $request
     * @return \Illuminate\Http\Response
     */
    public function indexSaleInvoice(SaleReturnInvoiceListRequest $request)
    {
        //
        $salesList = $this->salesService->getConfirmedSaleList($request);
        return view('return.invoice_index', compact('salesList'))->withInput(request()->input());
    }

    /**
    * Show the specified resource (sale, sale details)
    *
    * @param \App\Models\Sale $sale
    * @return \Illuminate\Http\Response
    */
    public function get_sale_detail(Sale $sale)
    {
        // sale details list,sale info and shop id
        $saleDetailsList = $this->salesDetailService->getSalesDetailBySaleId($sale->id);
        $saleInfo = $this->salesService->getSaleInfoById($sale->id);
        $shopId = $this->terminalService->getShopId($sale->terminal_id);
        
        // merge sale,shop,terminal and staff info
        $sale   = $this->salesService->mergeSaleDetailsInfo($sale, $saleInfo, $shopId);
        if (is_null($saleDetailsList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('return.return_entry', compact('sale', 'saleDetailsList'));
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \App\Http\Requests\SaleReturnRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function store(SaleReturnRequest $request)
    {
        // sale return id and check return statement
        $final_result = true;
        $inserted_ret_id = $this->returnService->insert($request);
        if (! is_numeric($inserted_ret_id)) {
            $final_result = false;
        }
        // sale return id is numeric and continue to sale return details to store in storage
        if (is_numeric($inserted_ret_id)) {
            $insertRetDetresult = $this->retDetailService->insert($request, $inserted_ret_id);
            if (! $insertRetDetresult) {
                $final_result = false;
            }
            // check sale return contains damage&loss qty and if damage&loss contain store in storage
            $final_result = $this->returnService->insertDamageLoss($inserted_ret_id, $request);
        }
        // check final result status
        if ($final_result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Sale Return Entry Data']));
            return redirect()->route('sale_return');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
    * Display the specified resource (sale return)
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function details(Request $request)
    {
        // sale return and check return statement
        $saleReturn  = $this->retDetailService->getReturnDetailById($request);
        if (is_null($saleReturn)) {
            return back()->with('error_status', __('message.E0001'));
        }
        // sale return details and check return statement
        $return_id = $request->return_id;
        $returnDetails = $this->returnService->getSaleReturnDetails($return_id);
        if (is_null($returnDetails)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('return.returndetails', compact('saleReturn', 'return_id', 'returnDetails'));
    }
}
