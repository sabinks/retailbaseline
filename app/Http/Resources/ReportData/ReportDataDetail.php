<?php

namespace App\Http\Resources\ReportData;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportDataDetail extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'assigned_date' => $this->assigned_date,
            'filled_date' => $this->filled_date,
            'reportdata_data' => $this->data,

            'report_title' => $this->report->title,
            'report_data' => $this->report->data,

            'entity_name' => $this->entities->name,
            'entity_address' => $this->entities->address,
            'entity_latitude' => $this->entities->latitude,
            'entity_longitude' => $this->entities->longitude,
            'entity_image' => $this->entities->image,
            
            'staff_name' => $this->staffDetail->name,
            'staff_email' => $this->staffDetail->email,
            'staff_image' => $this->staffDetail->profile_image,
            'staff_address' => $this->staffDetail->address,
        ];
    }
}
