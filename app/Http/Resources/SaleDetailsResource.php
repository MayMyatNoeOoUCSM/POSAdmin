<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleDetailsResource extends JsonResource
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
        $created_at = Carbon::parse($this->sale->create_datetime);
        return [
            'id' => $this->id,
            'product_id'=> (int)$this->product_id,
            'product_name' => (string) $this->product->name,
            'price' => (int)$this->price,
            'quantity' => (int) $this->quantity,
            'remark' => (string) $this->remark,
            'minutes' => $created_at->diffInMinutes($now),
            'sale_time' => $this->sale->create_datetime
        ];
    }
}
