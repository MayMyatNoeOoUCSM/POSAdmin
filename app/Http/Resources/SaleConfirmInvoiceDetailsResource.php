<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleConfirmInvoiceDetailsResource extends JsonResource
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
            'amount' => (int)$this->amount,
            'amount_tax'=> (int)$this->amount_tax,
            'sale_id' => (int) $this->id
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
            'message' => 'Successfully retrieved confirmed sale invoice details'
        ];
    }
}
