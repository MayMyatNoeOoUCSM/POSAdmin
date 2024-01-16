<?php

namespace App\Http\Controllers;

use App\Contracts\Services\WarehouseServiceInterface;
use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    private $warehouseService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\WarehouseServiceInterface $warehouseService
     * @return void
     */
    public function __construct(WarehouseServiceInterface $warehouseService)
    {
        $this->warehouseService = $warehouseService;
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
        //warehouse list
        $warehouseList = $this->warehouseService->getWarehouseList($request);
        if (is_null($warehouseList)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('warehouse.index', compact('warehouseList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //warehouse code to create a new warehouse
        $warhouseCode = $this->warehouseService->getWarehouseCode();
        return view('warehouse.create', compact('warhouseCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WarehouseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WarehouseRequest $request)
    {
        //store new warehouse info in storage and check return statement
        $result = $this->warehouseService->insert($request);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0001', ["tbl_name" => 'Warehouse']));
            return redirect()->route('warehouse');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Warehouse $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        return view('warehouse.edit', compact('warehouse'));
    }

    /**
     *
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\WarehouseRequest  $request
     * @param  \App\Models\Warehouse $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        //update warehouse info in storage and check return statement
        $result = $this->warehouseService->update($request, $warehouse);
        if ($result) {
            $request->session()->flash('success_status', __('message.I0002', ["tbl_name" => 'Warehouse']));
            return redirect()->route('warehouse');
        }
        return back()->with('error_status', __('message.E0001'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse $warehouse
     * @return \Illuminate\Http\Response
     */
    public function delete(Warehouse $warehouse)
    {
        $resultFlg = $this->warehouseService->delete($warehouse);
        //Stock exist in warehouse and currently can't not delete
        if ($resultFlg == 1) {
            return back()->with('error_status', __('message.W0004'));
        }
        if ($resultFlg == 2) {
            return redirect()->route('warehouse')->with('success_status', __('message.I0003', ["tbl_name" => 'Warehouse']));
        }
        //DB exception occured
        return back()->with('error_status', __('message.E0001'));
    }
}
