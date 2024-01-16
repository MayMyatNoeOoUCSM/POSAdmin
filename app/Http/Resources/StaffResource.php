<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
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
            'staff_number' => (string)$this->staff_number,
            'nrc_number' => (string) $this->nrc_number,
            'staff_type' => (string) ($this->staff_type==1?'full time':'part time'),
            'phone' => (string)$this->phone_number_1,
            'address' => (string)$this->address,
            'gender' => (string)$this->gender==1?'male':'female',
            'role' => (string)($this->role == 4? 'cashier': ($this->role == 5? 'kitchen':($this->role == 6? 'waiter': ''))),
            'dob' =>(string)$this->dob

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
