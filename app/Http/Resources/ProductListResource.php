<?php

namespace App\Http\Resources;

use Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $price = \App\Models\WarehouseShopProductRel::where("shop_id", Auth::user()->shop_id)
        //     ->where("product_id", $this->id)
        //     ->first();
        return [
            'id' => $this->id,
            'name' => (string)$this->name,
            'price' => (integer)$this->sale_price,
            'product_code' => (string)$this->product_code
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'status' => 'success',
            'message' => 'Successfully retrieved product'
        ];
    }
}
