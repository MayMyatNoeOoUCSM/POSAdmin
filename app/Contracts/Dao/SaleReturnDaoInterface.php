<?php

namespace App\Contracts\Dao;

/**
 * SaleReturnDaoInterface
 */
interface SaleReturnDaoInterface
{
    /**
     * Sale Return data is saved into storage
     *
     * @param \Illuminate\Http\Request $request
     */
    public function insert($request);

    /**
     * Get Sale Return List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $saleReturnList
     */
    public function getSaleReturnList($request);

    /**
     * Get Today Sale Return Count
     *
     * @return Integer
     */
    public function getSaleReturnByToday();

    /**
     * Get Sale Return Details
     *
     * @param Integer $id
     * @return Object
     */
    public function getSaleReturnDetails($id);

    /**
     * Get sale return list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleReturnList
     */
    public function getSaleReturnDataExport($request);
}
