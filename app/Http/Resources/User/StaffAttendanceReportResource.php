<?php

namespace App\Http\Resources\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffAttendanceReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'staff_name' => $this->staffDetail->name,
            'attendance_type' => $this->attendance_type,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'attendance_detail' => $this->attendance_detail ? $this->attendance_detail : '-',
            'from_date' => $this->from_date ? $this->from_date : '-',
            'to_date' => $this->to_date ? $this->to_date : '-',
            'region_name' => $this->staffRegion->name,
            'login_time' => Carbon::createFromFormat('Y-m-d H:i:s' , $this->login_time)->isoFormat('YYYY-MM-DD h:m:s A'),
            'saved_time' =>  Carbon::createFromFormat('Y-m-d H:m:s', $this->created_at)->setTimezone('Asia/Kathmandu')->toDateTimeString(),
        ];
    }
}
