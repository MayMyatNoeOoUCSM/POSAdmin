<?php

namespace App\Dao;

use App\Contracts\Dao\DamageLossDaoInterface;
use App\Models\Damage;
use App\Models\DamageDetail;
use App\Models\WarehouseShopProductRel;
use Illuminate\Support\Facades\DB;

/**
 * DamageLoss Dao
 *
 * @author
 */
class DamageLossDao implements DamageLossDaoInterface
{

    /**
     * Get damage&loss list search by shop or warehouse
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object $damageLossList
     */
    public function getDamageLossList($request)
    {
        $status = $request->shop_damage_search_status;
        if ($status == null or $status == config('constants.DAMAGE_LOSS_SEARCH_SHOP')) {
            // Shop Damage&Loss List
            $damageLossList = Damage::join('m_shop as shop', 'shop.id', '=', 't_damage_loss.shop_id')
                ->leftJoin('m_staff as stf', 't_damage_loss.create_user_id', '=', 'stf.id')
                ->leftJoin("t_damage_loss_details as tdld", "tdld.damage_loss_id", "=", "t_damage_loss.id");
            if (! empty($request->search_shop_name)) {
                $damageLossList = $damageLossList->where('shop.name', 'like', '%' . $request->search_shop_name . '%');
            }
            $damageLossList = $damageLossList->select(DB::raw("sum(tdld.quantity) as TotalDamageQty,count(t_damage_loss.id),t_damage_loss.shop_id,shop.name as shop_name"));
            $damageLossList = $damageLossList->groupby(["t_damage_loss.shop_id", "shop_name"]);
        } else {
            // Warehouse Damage&Loss List
            $damageLossList = Damage::join('m_warehouse as warehouse', 'warehouse.id', '=', 't_damage_loss.warehouse_id')
                ->leftJoin('m_staff as stf', 't_damage_loss.create_user_id', '=', 'stf.id')
                ->leftJoin("t_damage_loss_details as tdld", "tdld.damage_loss_id", "=", "t_damage_loss.id");
            if (! empty($request->search_warehouse_name)) {
                $damageLossList = $damageLossList->where('warehouse.name', 'like', '%' . $request->search_warehouse_name . '%');
            }
            $damageLossList = $damageLossList->select(DB::raw("COALESCE(sum(tdld.quantity),0) as TotalDamageQty,count(t_damage_loss.id),t_damage_loss.warehouse_id,warehouse.name as warehouse_name"));
            $damageLossList = $damageLossList->groupby(["t_damage_loss.warehouse_id", "warehouse_name"]);
        }

        $damageLossList = $damageLossList->paginate($request->custom_pg_size == "" ? config('constants.SALE_PAGINATION') : $request->custom_pg_size);

        return $damageLossList;
    }

    /**
     * Store damage&loss info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @return Integer $damage&loss id
     */
    public function insert($request)
    {
        $damage = new Damage();
        $damage->warehouse_id = ($request->warehouse_id ?? null);
        $damage->shop_id = ($request->shop_id ?? null);
        $damage->return_id = ($request->return_id ?? null);
        $damage->damage_loss_date = Date('Y-m-d H:i:s');
        $damage->is_deleted = config('constants.DEL_FLG_OFF');
        $damage->create_user_id = auth()->user()->id;
        $damage->update_user_id = auth()->user()->id;
        $damage->create_datetime = Date('Y-m-d H:i:s');
        $damage->update_datetime = Date('Y-m-d H:i:s');
        $damage->save();
        return $damage->id;
    }

    /**
     * Get damage&loss quantity for today
     * @return Integer
     */
    public function getDamageLossByToday()
    {
        return Damage::where("damage_loss_date", "=", date("Y-m-d"))->get()->count();
    }

    public function getProductStockQty($request, $product_id, $i)
    {
        $productStockQty = WarehouseShopProductRel::join("m_product", "m_product.id", "t_warehouse_shop_product.product_id")
            ->where("t_warehouse_shop_product.product_id", "=", $product_id)
            ->where(function ($query) use ($request) {
                if ($request->warehouse_id) {
                    $query->where("t_warehouse_shop_product.warehouse_id", "=", $request->warehouse_id);
                }
                if ($request->warehouse_id) {
                    $query->where("t_warehouse_shop_product.shop_id", "=", $request->shop_id);
                }
            })
            ->where("t_warehouse_shop_product.quantity", ">=", $request->qty[$i])
            ->first();
        return $productStockQty;
    }

    /**
     * Get damageloss list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $damagelossList
     */
    public function getDamageLossDataExport($request)
    {
        $damage_loss = DamageDetail::join("t_damage_loss", "t_damage_loss.id", "=", "t_damage_loss_details.damage_loss_id")
            ->leftjoin("m_product", "m_product.id", "=", "t_damage_loss_details.product_id")
            ->leftjoin("m_shop", "m_shop.id", "=", "t_damage_loss.shop_id")
            ->select(\DB::raw(" m_shop.name as shop_name, m_product.name as product_name, sum(t_damage_loss_details.quantity) as damageloss_quantity, (CASE WHEN t_damage_loss_details.product_status = '1' THEN 'DAMAGE' ELSE 'LOSS' END) AS type"))
            ->where(function ($q) use ($request) {
                if (! empty($request->shop_id)) {
                    $q->where("m_shop.id", "=", $request->shop_id);
                }

                if (! empty($request->damage)) {
                    $q->where("t_damage_loss_details.product_status", "=", $request->damage);
                } elseif (! empty($request->loss)) {
                    $q->where("t_damage_loss_details.product_status", "=", $request->loss);
                }

                if (! empty($request->from_date) && ! empty($request->to_date)) {
                    $q->whereBetween('t_damage_loss.damage_loss_date', [$request->from_date, $request->to_date]);
                }
            })
            ->whereNull("t_damage_loss.warehouse_id")
            ->groupBy("damage_loss_id", "shop_name", "product_name", "t_damage_loss_details.product_status")
            ->get();
        $damage_loss->header = ["Shop Name", "Product Name", "Total Damage&Loss Quantity", "Type"];
        return $damage_loss;
    }
}
