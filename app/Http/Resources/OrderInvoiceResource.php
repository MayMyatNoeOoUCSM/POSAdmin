<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderInvoiceResource extends JsonResource
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
            'invoice_no' => (string)$this->invoice_number,
            'date' => $this->order_date,
            'amount' => (int)$this->amount,
            'amount_tax'=> (int)$this->amount_tax,
            'paid_amount'=> (int)$this->paid_amount,
            'change_amount'=> (int)$this->change_amount,

            // 'order_status' => $this->order_status,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            //'order_items' => OrderDetailsResource::collection($this->details),
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
            'message' => 'Successfully retrieved order invoice details list'
        ];
    }
}
