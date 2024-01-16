<?php

namespace App\Contracts\Services;

interface DamageDetailServiceInterface
{

    /**
     * Get total product count from damage&loss details search by product id
     *
     * @param Integer $productId
     * @return Integer product count
     */
    public function getDamageDetailByProductId($productId);

    /**
     * Store multiple damage&loss details info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $damage_loss_id
     * @return Object $damageDetail
     */
    public function insert($request, $damage_loss_id);

    /**
     * Store single damage&loss details info in storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $damage_loss_id
     * @return Object $damageDetail
     */
    public function insertOneRecord($request, $damage_loss_id);

    /**
     * Get damage&loss details list search by shop id or warehouse id and request
     *
     * @param   Integer $shop_id
     * @param   Integer $warehouse_id
     * @param   \Illuminate\Http\Request $request
     * @return  Object $damageLossList
     */
    public function details($shop_id, $warehouse_id, $request);
}
