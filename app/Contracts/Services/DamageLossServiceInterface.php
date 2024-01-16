<?php

namespace App\Contracts\Services;

interface DamageLossServiceInterface
{
    /**
     * Get Damage&Loss List from table
     *
     * @return DamageLoss damageLossList
     */
    public function getDamageLossList($request);

    /**
     * Store Damage&Loss info in storage
     *
     * @param \App\Http\Requests\DamageLossRequest $request
     * @param Integer $sourceFrom
     * @return Integer $damage&loss id
     */
    public function insert($request, $sourceFrom);

    /**
     * Get damage&loss total count for today
     *
     * @return Integer
     */
    public function getDamageLossByToday();

    /**
     * Get damageloss list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $damagelossList
     */
    public function getDamageLossDataExport($request);
}
