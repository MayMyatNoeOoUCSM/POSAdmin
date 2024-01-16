<?php

namespace App\Dao;

use App\Contracts\Dao\DamageDetailDaoInterface;
use App\Models\Damage;
use App\Models\DamageDetail;
use App\Models\WarehouseShopProductRel;
use Illuminate\Support\Facades\DB;

/**
 * Damage&Loss Details Dao
 *
 * @author
 */
class DamageDetailDao implements DamageDetailDaoInterface
{

    /**
     * Get total product count from damage&loss details search by product id
     *
     * @param Integer $productId
     * @return Integer product count
     */
    public function getDamageDetailByProductId($productId)
    {
        $damageProductCount = DamageDetail::where('product_id', $productId)->count();
        return $damageProductCount;
    }

    /**
     * Store multiple damage&loss details info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $damage_loss_id
     * @return Object $damageDetail
     */
    public function insert($request, $damage_loss_id)
    {
        for ($i = 0; $i < count($request->qty); $i++) {
            $damageDetail = new DamageDetail();
            $damageDetail->damage_loss_id = $damage_loss_id;
            $damageDetail->quantity = $request->qty[$i];
            $damageDetail->product_id = $request->product_id[$i];
            $damageDetail->price = $request->price[$i];
            $damageDetail->product_status = $request->product_status[$i];
            $damageDetail->remark = $request->remark[$i];
            $damageDetail->save();
            WarehouseShopProductRel::where('product_id', '=', $request->product_id[$i])
                ->where('warehouse_id', '=', $request->warehouse_id ?? null)
                ->where('shop_id', '=', $request->shop_id ?? null)
                ->decrement('quantity', $request->qty[$i]);
        }
        return $damageDetail;
    }

    /**
     * Store single damage&loss details info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $damage_loss_id
     * @return Object $damageDetail
     */
    public function insertOneRecord($request, $damage_loss_id)
    {
        $damageDetail = new DamageDetail();
        $damageDetail->damage_loss_id = $damage_loss_id;
        $damageDetail->quantity = $request->quantity;
        $damageDetail->product_id = $request->product_id;
        $damageDetail->price = $request->price;
        $damageDetail->product_status = $request->product_status;
        $damageDetail->remark = $request->remark;
        $damageDetail->save();

        // dd($request->product_id);

        WarehouseShopProductRel::where('product_id', '=', $request->product_id)
            ->where('warehouse_id', '=', null)
            ->where('shop_id', '=', $request->shop_id ?? null)
            ->decrement('quantity', $request->quantity);
        return $damageDetail;
    }

    /**
     * Get damage&loss details list search by shop id or warehouse id and request
     *
     * @param   Integer $shop_id
     * @param   Integer $warehouse_id
     * @param   \Illuminate\Http\Request $request
     * @return  Object $damageLossList
     */
    public function details($shop_id, $warehouse_id, $request)
    {
        // search by shop
        if ($shop_id != 0) {
            $damageLossList = Damage::leftJoin('m_shop as shop', 'shop.id', '=', 't_damage_loss.shop_id')
                ->leftJoin("t_damage_loss_details as tdld", "tdld.damage_loss_id", "=", "t_damage_loss.id")
                ->leftJoin("m_product", "m_product.id", "=", "tdld.product_id")
                ->select(DB::raw("t_damage_loss.return_id,t_damage_loss.damage_loss_date,shop.name as shop_name,
                                    m_product.name as product_name,tdld.quantity as totaldamageqty,
                                    (CASE WHEN tdld.product_status='1' THEN 'Damage' ELSE 'Loss' END) as product_status
                                    "))
                ->where("t_damage_loss.shop_id", "=", $shop_id)
                ->paginate(config('constants.DAMAGE_LOSS_PAGINATION'));
        } else { // search by warehouse
            $damageLossList = Damage::leftJoin('m_warehouse as warehouse', 'warehouse.id', '=', 't_damage_loss.warehouse_id')
                ->leftJoin("t_damage_loss_details as tdld", "tdld.damage_loss_id", "=", "t_damage_loss.id")
                ->leftJoin("m_product", "m_product.id", "=", "tdld.product_id")
                ->select(DB::raw("t_damage_loss.damage_loss_date,warehouse.name as warehouse_name,
                                    m_product.name as product_name,tdld.quantity as totaldamageqty,
                                    (CASE WHEN tdld.product_status='1' THEN 'Damage' ELSE 'Loss' END) as product_status
                                    "))
                ->where("t_damage_loss.warehouse_id", "=", $warehouse_id)
                ->paginate($request->custom_pg_size == "" ? config('constants.DAMAGE_LOSS_PAGINATION') : $request->custom_pg_size);
        }
        return $damageLossList;
    }
}
