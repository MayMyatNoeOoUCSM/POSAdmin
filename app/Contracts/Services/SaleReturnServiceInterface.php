<?php

namespace App\Contracts\Services;

interface SaleReturnServiceInterface
{
    /**
    * Get sale return list
    *
    * @return Object $returnList
    */
    public function getSaleReturnList($request);

    /**
    * Store sale return info in storage
    *
    * @param \Illuminate\Http\Request $request
    * @return Integer
    */
    public function insert($request);

    /**
     * Get total sale return quantity for today
     *
     * @return Integer
    */
    public function getSaleReturnByToday();

    /**
     * Get sale return details info search by return id
     *
     * @param  Integer $return_id
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

    /**
     * Store damage&loss
     *
     * @param  Integer $return_id
     * @param  \App\Http\Requests\SaleReturnRequest $request
     * @return Boolean
     */
    public function insertDamageLoss($return_id, $request);
}
