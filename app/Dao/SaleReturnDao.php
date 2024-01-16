<?php

namespace App\Dao;

use App\Contracts\Dao\SaleReturnDaoInterface;
use App\Models\ReturnDetail;
use App\Models\Sale;
use App\Models\SalesReturn;
use Illuminate\Support\Facades\DB;

/**
 * Sales Return Dao
 *
 * @author
 */
class SaleReturnDao implements SaleReturnDaoInterface
{

    /**
     * Get sale return list
     *
     * @return Object $returnList
     */
    public function getSaleReturnList($request)
    {
        $returnList = SalesReturn::leftJoin('t_sale as sale', function ($join) {
            $join->on('t_return.sale_id', '=', 'sale.id');
            $join->on('t_return.return_invoice_number', '=', 'sale.invoice_number');
        })
            ->leftJoin('m_terminal as t', 't.id', '=', 'sale.terminal_id')
            ->leftJoin('m_shop as shop', 'shop.id', '=', 't.shop_id')
            ->leftJoin('m_staff as stf', 'sale.create_user_id', '=', 'stf.id');
        // invoice no
        if (! empty($request->search_ret_invoice_no)) {
            $returnList = $returnList->where('t_return.return_invoice_number', 'like', '%' . $request->search_ret_invoice_no . '%');
        }
        // from date
        if (! empty($request->search_sale_date_from)) {
            $returnList = $returnList->whereDate('t_return.return_date', '>=', $request->search_sale_date_from);
        }
        // to date
        if (! empty($request->search_sale_date_to)) {
            $returnList = $returnList->whereDate('t_return.return_date', '<=', $request->search_sale_date_to);
        }
        // staff name
        if (! empty($request->search_staff_name)) {
            $returnList = $returnList->where('stf.name', 'like', '%' . $request->search_staff_name . '%');
        }
        $returnList = $returnList->select(DB::raw('t_return.sale_id,t_return.return_date,t_return.id as ret_id,t_return.return_invoice_number,sale.sale_date,stf.name as staff_name,shop.name as shop_name'));
        $returnList = $returnList->paginate($request->custom_pg_size == "" ? config('constants.SALE_RETURN_PAGINATION') : $request->custom_pg_size);
        foreach ($returnList as $item) {
            $total_ret_qty = $this->getTotalReturnQtyByReturnId($item->ret_id);
            $total_sale_qty = $this->getTotalSaleQtyBySaleId($item->sale_id);
            $item->total_return_qty = $total_ret_qty;
            $item->total_sale_qty = $total_sale_qty;
        }
        return $returnList;
    }

    /**
     * Store sale return info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Integer
     */
    public function insert($request)
    {
        $return = new SalesReturn();
        $return->shop_id = $request->shop_id;
        $return->return_invoice_number = $request->ret_invoice_no;
        $return->sale_id = $request->ret_sale_id;
        $return->return_date = $request->return_date;
        $return->is_deleted = config('constants.DEL_FLG_OFF');
        $return->create_user_id = auth()->user()->id;
        $return->update_user_id = auth()->user()->id;
        $return->create_datetime = Date('Y-m-d H:i:s');
        $return->update_datetime = Date('Y-m-d H:i:s');
        $return->save();
        return $return->id;
    }

    /**
     * Get total return quantity search by return id
     *
     * @param  Integer $ret_id
     * @return Object
     */
    public function getTotalReturnQtyByReturnId($ret_id)
    {
        $query = SalesReturn::leftJoin('t_return_details as detail', 't_return.id', '=', 'detail.return_id')
            ->where('detail.return_id', '=', $ret_id)
            ->groupBy('detail.return_id')
            ->select(DB::raw('detail.return_id,SUM(detail.quantity) as total_ret_qty'))
            ->get();
        if (! empty($query)) {
            $retValue = $query->pluck('total_ret_qty')->first();
            return $retValue;
        }
    }

    /**
     * Get total sale quantity search by sale id
     *
     * @param  Integer $sale_id
     * @return Object
     */
    public function getTotalSaleQtyBySaleId($sale_id)
    {
        $query = Sale::leftJoin('t_sale_details as detail', 't_sale.id', '=', 'detail.sale_id')
            ->where('detail.sale_id', '=', $sale_id)
            ->groupBy('detail.sale_id')
            ->select(DB::raw('detail.sale_id,SUM(detail.quantity) as total_sale_qty'))
            ->get();
        if (! empty($query)) {
            $retValue = $query->pluck('total_sale_qty')->first();
            return $retValue;
        }
    }

    /**
     * Get total sale return quantity for today
     *
     * @return Integer
     */
    public function getSaleReturnByToday()
    {
        return SalesReturn::where("return_date", "=", date("Y-m-d"))->get()->count();
    }

    /**
     * Get sale return details info search by return id
     *
     * @param  Integer $return_id
     * @return Object
     */
    public function getSaleReturnDetails($return_id)
    {
        return SalesReturn::where('id', '=', $return_id)->first();
    }

    /**
     * Get sale return list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleReturnList
     */
    public function getSaleReturnDataExport($request)
    {
        $salereturn = ReturnDetail::join("t_return", "t_return.id", "=", "t_return_details.return_id")
            ->join("t_sale", "t_sale.id", "=", "t_return.sale_id")
            ->join("m_terminal", "m_terminal.id", "=", "t_sale.terminal_id")
            ->join("m_shop", "m_shop.id", "=", "m_terminal.shop_id")
            ->join("m_product", "m_product.id", "=", "t_return_details.product_id")
            ->select(\DB::raw("t_sale.sale_date as sale_date,t_return.return_date as return_date, sum(t_return_details.quantity) as quantity"))
            ->where(function ($q) use ($request) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }
                if (! empty($request->from_date) && ! empty($request->to_date)) {
                    $q->whereBetween('t_return.return_date', [$request->from_date, $request->to_date]);
                }
            })
            ->groupBy("return_id", "sale_date", "return_date")
            ->get();
        $salereturn->header = ["Sale Date", "Return Date", "Return Quantity"];
        return $salereturn;
    }
}
