<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TerminalResource extends JsonResource
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
            'terminal_name' => (string) $this->name,
            'shop_name' => (string) $this->shop->name
        ];
    }
}
