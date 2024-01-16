<?php

namespace App\Services;

use App\Contracts\Dao\ReturnDetailDaoInterface;
use App\Contracts\Services\ReturnDetailServiceInterface;

class ReturnDetailService implements ReturnDetailServiceInterface
{
    private $returnDetailDao;

    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\ReturnDetailDaoInterface $retDetailDao
     * @return void
     */
    public function __construct(ReturnDetailDaoInterface $retDetailDao)
    {
        $this->returnDetailDao = $retDetailDao;
    }

    /**
     * Get sale return details info search by return_id
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object
     */
    public function getReturnDetailById($returnId)
    {
        return $this->returnDetailDao->getReturnDetailById($returnId);
    }

    /**
     * Store sale return details info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $return_id
     * @return Integer $return_id
     */
    public function insert($request, $return_id)
    {
        return $this->returnDetailDao->insert($request, $return_id);
    }
}
