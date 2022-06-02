<?php

namespace App\Http\Resources\EntityData;

use Illuminate\Http\Resources\Json\JsonResource;

class EntityLocationResource extends JsonResource
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
            'name' => $this->name,
            'lat' => $this->latitude,
            'lng' => $this->longitude,
            'assigned' => false,
        ];
    }
}
