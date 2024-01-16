<?php

namespace App\Contracts\Dao;

/**
 * ReturnDetailDaoInterface
 */
interface ReturnDetailDaoInterface
{
    /**
     * Get SaleReturn Detail List  by ReturnId from storage
     *
     * @param Integer $returnId
     * @return Object returnDetailList
     */
    public function getReturnDetailById($returnId);

    /**
     * Sale Return Detail data is saved into storage
     *
     * @param \Illuminate\Http\Request $request
     * @param Integer $return_id
     */
    public function insert($request, $return_id);
}
