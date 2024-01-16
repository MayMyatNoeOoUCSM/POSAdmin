<?php

namespace App\Contracts\Dao;

/**
 * DamageLossDaoInterface
 */
interface DamageLossDaoInterface
{
    /**
     * Get Damage&Loss List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object
     */
    public function getDamageLossList($request);

    /**
     * DamageLoss data is saved into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Integer $damage&loss id
     */
    public function insert($request);

    /**
     * Get Today Damage & Loss Count from sotrage
     *
     * @return Integer
     */
    public function getDamageLossByToday();

    public function getProductStockQty($request, $product_id, $i);

    /**
     * Get damageloss list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $damagelossList
     */
    public function getDamageLossDataExport($request);
}
