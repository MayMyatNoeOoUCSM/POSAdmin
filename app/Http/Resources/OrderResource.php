<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'date' => $this->order_date,
            'order_status' => $this->order_status,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'invoice_number' => (string) $this->invoice_number,
            'order_items' => OrderDetailsResource::collection($this->details),
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
            'message' => 'Successfully retrieved order detail'
        ];
    }
}
