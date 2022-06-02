<?php

namespace App\Http\Resources\ReportData;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportDataList extends JsonResource
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
            'title' => $this->report->title,
            'entity_name' => $this->entities->name,
            'assigned_staff' => $this->staffDetail->name,
            'staff_email' => $this->staffDetail->email,
            'assigned_date' => Carbon::parse($this->assigned_date, 'UTC')->format('M d, Y'),
            'filled_date' => Carbon::parse($this->filled_date, 'UTC')->format('M d, Y'),
            'status' => $this->statusConversion($this->status),
            'options' => '<a class="btn btn-secondary btn-sm mr-1" href="/report-info/detail/' . $this->id . '"><i class="fa fa-eye"></i></a>' .
            '<a class="btn btn-danger btn-sm mr-1" onClick="deleteReportData(' . $this->id . ')"><i class="fa fa-trash"></i></a>'
        ];
    }
    public function statusConversion($status){
        switch ($status) {
            case 1:
                return 'Assigned';
            case 2:
                return 'Pending';
            case 3:
                return 'Approved';
            case 4:
                return 'Rejected';
        }
    }
}
