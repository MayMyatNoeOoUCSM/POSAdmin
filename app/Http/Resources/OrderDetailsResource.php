<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $now = Carbon::now();
        $created_at = Carbon::parse($this->order->create_datetime);
        return [
            'id' => $this->id,
            'product_name' => (string) $this->product->name,
            'price' => (int)$this->price,
            'quantity' => (int) $this->quantity,
            'remark' => (string) $this->remark,
            'order_details_status' => (int) $this->order_details_status,
            'table_name'=>(string)$this->order->restauranttable->name,
            'minutes' => $created_at->diffInMinutes($now),
            'order_time' => $this->order->create_datetime,
            'invoice_number' => (string)$this->order->invoice_number,
            'amount' => $this->price * $this->quantity
        ];
    }
}
