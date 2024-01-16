<?php

namespace App\Dao;

use App\Contracts\Dao\SaleDetailDaoInterface;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\DB;

/**
 * Sales Details Dao
 *
 * @author
 */
class SaleDetailDao implements SaleDetailDaoInterface
{

    /**
     * Get total sale count search by product id
     *
     * @param Integer $productId
     * @return Integer $salesDetailCount
     */
    public function getSalesDetailByProductId($productId)
    {
        $salesDetailCount = SaleDetail::where('product_id', $productId)->count();
        return $salesDetailCount;
    }

    /**
     * Get sale details list
     *
     * @param  Integer $saleId
     * @return Object $salesDetailList
     */
    public function getSalesDetailBySaleId($saleId)
    {
        $salesDetailList = Sale::where('t_sale.id', $saleId)
            ->join("t_sale_details as sd", "sd.sale_id", "=", "t_sale.id")
            ->join('m_product as p', 'p.id', '=', 'sd.product_id')
            ->leftjoin("t_return as tr", "tr.sale_id", "=", "t_sale.id")
            ->leftjoin("t_return_details as trd", "trd.return_id", "=", "tr.id")
            ->select(
                "sd.*",
                "p.name as product_name",
                DB::raw("(Select coalesce(SUM(t_return_details.quantity),0) as total_return_qty FROM t_return_details
                                  RIGHT JOIN t_return ON t_return_details.return_id = t_return.id
                                  WHERE t_return_details.product_id = sd.product_id
                                  AND t_return.sale_id = $saleId
                                  GROUP BY t_return_details.product_id
                            )")
            )
            ->groupBy(["sd.id", "product_name"])
            ->get();

        return $salesDetailList;
    }

    /**
     * Get sale detail list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleDetailList
     */
    public function getSaleDetailListForExport($sale)
    {
        // $salesDetailList = Sale::where('t_sale.id', $sale->id)
        //     ->join("t_sale_details as sd", "sd.sale_id", "=", "t_sale.id")
        //     ->join('m_product as p', 'p.id', '=', 'sd.product_id')
        //     ->select(
        //         "p.name as product_name",
        //         "sd.price", "sd.quantity", "sd.remark"
        //     )
        //     ->get();
        //$salesDetailList->header= ["Product Name", "Price", "Quanttiy", "Remark"];
        $salesDetailList = Sale::where('t_sale.id', $sale->id)
            ->join("t_sale_details as sd", "sd.sale_id", "=", "t_sale.id")
            ->join('m_product as p', 'p.id', '=', 'sd.product_id')
            ->join('m_staff as stf', 't_sale.create_user_id', '=', 'stf.id')
            ->join('m_terminal as t', 't_sale.terminal_id', '=', 't.id')
            ->select(DB::raw("t_sale.invoice_number as invoice_number,t_sale.sale_date as sale_date,stf.name as staff_name,t.name as terminal_name,t_sale.amount,(t_sale.amount+t_sale.amount_tax)as total, p.name as product_name,sd.quantity, sd.price, sd.remark"))
            ->get();
        return $salesDetailList;
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
        for ($i = 0; $i < count($request->data['product_id']); $i++) {
            $orderDetails = SaleDetail::where('sale_id', $sale_id)
                ->where('product_id', $request->data['product_id'][$i])
                ->first();
            if (! $orderDetails) {
                $orderDetails = new SaleDetail();
            }
            $orderDetails->sale_id = $sale_id;
            $orderDetails->product_id = $request->data['product_id'][$i];
            $orderDetails->price = $request->data['price'][$i];
            $orderDetails->quantity = $request->data['quantity'][$i];
            $orderDetails->remark = $request->data['remark'][$i];
            $orderDetails->save();
        }
        return $sale_id;
    }

    /**
     * Remove sale details info in storage
     *
     * @param  Integer $sale_id
     * @param  Integer $product_id
     * @return Object $orderDetails
     */
    public function removeSaleDetails($sale_id, $product_id)
    {
        $saleDetails = SaleDetail::where('sale_id', $sale_id)
            ->where('product_id', $product_id)
            ->first();
        if (! $saleDetails) {
            return false;
        }
        $saleDetails->delete();

        return $saleDetails;
    }
}
