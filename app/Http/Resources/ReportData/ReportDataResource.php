<?php

namespace App\Http\Resources\ReportData;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportDataResource extends JsonResource
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
            'entity_name' => $this->entities->name,
            'entity_address' => $this->entities->address,
            'entity_latitude' => $this->entities->latitude,
            'entity_longitude' => $this->entities->longitude,
            'region_name' => $this->region->name,
            'filled_date' => $this->filled_date,
            'filled_by' => $this->staffDetail->name,
            'assigned_date' => $this->assigned_date,
            'question' => json_decode($this->report->data),
            'answer' => json_decode($this->data),
        ];
    }
}
