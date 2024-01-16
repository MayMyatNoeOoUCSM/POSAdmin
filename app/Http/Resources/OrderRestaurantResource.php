<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderRestaurantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'order_id' => $this->id,
            'table_name' => (string)$this->restauranttable->name,
            'order_status' => $this->order_status==config('constants.ORDER_ACCEPT') ? 'accept':'confirm'
        ];
    }
}
