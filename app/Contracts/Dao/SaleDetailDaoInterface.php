<?php

namespace App\Contracts\Dao;

/**
 * SaleDetailDaoInterface
 */
interface SaleDetailDaoInterface
{

    /**
     * Get Pending Sales Count from Sales
     *
     * @param Integer $productId
     * @return Integer count
     */
    public function getSalesDetailByProductId($productId);

    /**
     * Get Sale Detail by SaleId from storage
     *
     * @param Integer $saleId
     * @return Object salesDetailList
     */
    public function getSalesDetailBySaleId($saleId);

    /**
     * Get sale detail list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleDetailList
     */
    public function getSaleDetailListForExport($sale);

    /**
    * Insert sale details info in storage
    *
    * @param Integer $sale_id
    * @param  \Illuminate\Http\Request $request
    * @return Integer $sale_id
    */
    public function insertSaleDetails($sale_id, $request);

    /**
     * Remove sale details info in storage
     *
     * @param  Integer $sale_id
     * @param  Integer $product_id
     * @return Object $saleDetails
     */
    public function removeSaleDetails($sale_id, $product_id);
}
