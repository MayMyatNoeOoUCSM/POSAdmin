<?php

namespace App\Dao;

use App\Contracts\Dao\ReturnDetailDaoInterface;
use App\Models\ReturnDetail;

/**
 * Sale Return Detail Dao
 *
 * @author
 */
class ReturnDetailDao implements ReturnDetailDaoInterface
{

    /**
     * Get sale return details info search by return_id
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $returnDetailList
     */
    public function getReturnDetailById($request)
    {
        $returnDetailList = ReturnDetail::where('return_id', $request->return_id)
            ->leftJoin('m_product as p', 'p.id', '=', 't_return_details.product_id')
            ->select('p.name as product_name', 't_return_details.*')
            ->paginate($request->custom_pg_size == "" ? config('constants.SALE_RETURN_PAGINATION') : $request->custom_pg_size);
        return $returnDetailList;
    }

    /**
     * Store sale return details info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $return_id
     * @return Object $returnDetail
     */
    public function insert($request, $return_id)
    {
        for ($i = 0; $i < count($request->qty); $i++) {
            $returnDetail = new ReturnDetail();
            $returnDetail->return_id = $return_id;
            $returnDetail->quantity = $request->qty[$i];
            $returnDetail->product_id = $request->product_id[$i];
            $returnDetail->price = $request->price[$i];
            $returnDetail->remark = $request->reason[$i];
            $returnDetail->save();
        }
        return $returnDetail;
    }
}
