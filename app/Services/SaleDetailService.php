<?php

namespace App\Services;

use App\Contracts\Dao\SaleDetailDaoInterface;
use App\Contracts\Services\SaleDetailServiceInterface;

class SaleDetailService implements SaleDetailServiceInterface
{
    private $salesDetailDao;

    /**
     * Class Constructor
     *
     * @param \App\Contracts\Dao\SaleDetailDaoInterface $salesDetailDao
     * @return void
     */
    public function __construct(SaleDetailDaoInterface $salesDetailDao)
    {
        $this->salesDetailDao = $salesDetailDao;
    }

    /**
     * Get total sale count search by product id
     *
     * @param Integer $productId
     * @return Integer
     */
    public function getSalesDetailByProductId($productId)
    {
        return $this->salesDetailDao->getSalesDetailByProductId($productId);
    }

    /**
     * Get sale details list
     *
     * @param  Integer $saleId
     * @return Object $salesDetailList
     */
    public function getSalesDetailBySaleId($saleId)
    {
        return $this->salesDetailDao->getSalesDetailBySaleId($saleId);
    }

    /**
     * Get sale detail list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleDetailList
     */
    public function getSaleDetailListForExport($sale)
    {
        return $this->salesDetailDao->getSaleDetailListForExport($sale);
    }

    /**
     * Insert sale details info in storage
     *
     * @param Integer $sale_id
     * @param  \Illuminate\Http\Request $request
     * @return Integer $sale_id
     */
    public function insertSaleDetails($sale_id, $request)
    {
        return $this->salesDetailDao->insertSaleDetails($sale_id, $request);
    }

    /**
     * Remove sale details info in storage
     *
     * @param  Integer $sale_id
     * @param  Integer $product_id
     * @return Object $saleDetails
     */
    public function removeSaleDetails($sale_id, $product_id)
    {
        return $this->salesDetailDao->removeSaleDetails($sale_id, $product_id);
    }
}
