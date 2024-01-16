<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
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
            'id'    => $this->id,
            'name'  => (string)$this->name,
            'total_seats_people'    => $this->total_seats_people,
            'available_status'  => (string) $this->available_status==config('constants.RESTAURANT_TABLE_FREE')? 'free':'order',
            'current_order_id'  => $this->available_status!=config('constants.RESTAURANT_TABLE_FREE') ?
                                  ($this->order->where('order_status', '!=', config('constants.ORDER_INVOICE'))->first() != null ? $this->order->where('order_status', '!=', config('constants.ORDER_INVOICE'))->first()->id:0) : 0,
            'order_items' => $this->available_status!=config('constants.RESTAURANT_TABLE_FREE') ?
                             OrderDetailsResource::collection($this->orderdetails) : []

        ];
    }
}
