<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantTableListAtKitchenResource extends JsonResource
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
            'id' => $this->id,
            'name' => (string)$this->name,
            'total_seats_people' => $this->total_seats_people,
            'current_order_id' => $this->order->where('order_status', '!=', config('constants.ORDER_INVOICE'))
                ->first() != null ?
                                  $this->order->where('order_status', '!=', config('constants.ORDER_INVOICE'))
                                      ->first()->id : 0,

            'order_items'=> OrderDetailsResource::collection($this->orderdetails->where('order_details_status', config('constants.ORDER_DETAILS_CREATE')))
        
        ];
    }
}
