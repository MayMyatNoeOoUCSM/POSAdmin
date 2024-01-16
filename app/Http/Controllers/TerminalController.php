<?php

namespace App\Http\Controllers;

use App\Contracts\Services\SaleServiceInterface;
use App\Contracts\Services\ShopServiceInterface;
use App\Contracts\Services\TerminalServiceInterface;
use App\Http\Requests\TerminalRequest;
use App\Models\Terminal;
use Illuminate\Http\Request;

class TerminalController extends Controller
{
    private $terminalService;
    private $salesService;
    private $shopService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\TerminalServiceInterface $terminalService
     * @param \App\Contracts\Services\SalesServiceInterface $salesService
     * @param \App\Contracts\Services\ShopServiceInterface $shopService
     * @return void
     */
    public function __construct(TerminalServiceInterface $terminalService, SaleServiceInterface $salesService, ShopServiceInterface $shopService)
    {
        $this->terminalService = $terminalService;
        $this->salesService = $salesService;
        $this->shopService = $shopService;
    }

    /**
     *
     * Display a listing of the resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //terminal list
        $terminalList = $this->terminalService->getTerminalList($request);
        if (is_null($terminalList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('terminal.index', compact('terminalList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //shop list object to create new terminal
        $shopList = $this->shopService->getAllShopList();
        return view('terminal.create', compact('shopList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TerminalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TerminalRequest $request)
    {
        //store new terminal info in storage and check return statement
        $result = $this->terminalService->insert($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Terminal']));
            return redirect()->route('terminal');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Terminal $terminal
     * @return \Illuminate\Http\Response
     */
    public function edit(Terminal $terminal)
    {
        //shop list object to update terminal info in storage
        $shopList = $this->shopService->getAllShopList();
        return view('terminal.edit', compact('terminal', 'shopList'));
    }

    /**
     *
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TerminalRequest  $request
     * @param  \App\Models\Terminal $terminal
     * @return \Illuminate\Http\Response
     */
    public function update(TerminalRequest $request, Terminal $terminal)
    {
        //update terminal info in storage and check return statement
        $result = $this->terminalService->update($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'Terminal']));
            return redirect()->route('terminal');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Terminal $terminal
     * @return \Illuminate\Http\Response
     */
    public function delete(Terminal $terminal)
    {
        //check related sale count for terminal
        $salesCount = $this->salesService->getPendingSalesCount($terminal->id);
        if ($salesCount > 0) {
            return back()->with('error_status', __('message.W0001'));
        }
        //remove terminal from storage and check return statement
        $terminal = $this->terminalService->delete($terminal);
        if ($terminal) {
            return redirect()->route('terminal')->with('success_status', __('message.I0003', ["tbl_name" => 'Terminal']));
        }
        return back()->with('error_status', __('message.E0001'));
    }
}
