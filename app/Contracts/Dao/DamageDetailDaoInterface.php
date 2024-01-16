<?php

namespace App\Contracts\Dao;

/**
 * DamageDetailDaoInterface
 */
interface DamageDetailDaoInterface
{

    /**
     * Get damage detail product count from storage
     *
     * @param Integer $productId
     * @return Integer count
     */
    public function getDamageDetailByProductId($productId);

    /**
     * DamageLoss Detail data is saved into storage
     *
     * @param \Illuminage\Http\Request $request
     * @param Integer $damage_loss_id
     * @return Object $damageDetail
     */
    public function insert($request, $damage_loss_id);

    /**
     * Damage & Loss Insert One Record into storage
     *
     * @param \Illuminate\Http\Request $request
     * @param  Integer $damage_loss_id
     * @return Object $damageDetail
     */
    public function insertOneRecord($request, $damage_loss_id);

    /**
     * Damage & Loss Details from storage
     *
     * @param Integer $shop_id
     * @param Integer $warehouse_id
     * @param \Illuminate\Http\Request $request
     * @return  Object
     */
    public function details($shop_id, $warehouse_id, $request);
}
