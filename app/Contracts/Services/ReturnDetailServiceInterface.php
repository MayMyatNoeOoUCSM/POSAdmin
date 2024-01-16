<?php

namespace App\Contracts\Services;

interface ReturnDetailServiceInterface
{
    /**
     * Get sale return details info search by return_id
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object
     */
    public function getReturnDetailbyId($returnId);

    /**
     * Store sale return details info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $return_id
     * @return Integer $return_id
     */
    public function insert($request, $return_id);
}
