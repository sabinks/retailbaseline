<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Resources\Json\JsonResource;

class InwardStockResource extends JsonResource
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
            'entry_date' => $this->entry_date,
            'type' => 2,
            'stock_type' => 'Inward Stock',
            'particular' => $this->particular,
            'quantity' => $this->quantity
        ];
    }
}
