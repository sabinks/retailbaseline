<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffAttendanceResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'staff_name' => $this->staffDetail->name,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'remark' => $this->remark,
            'attendance_type' => $this->attendance_type,
            'attendance_detail' => $this->attendance_detail ? $this->attendance_detail : '-',
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'login_time' => Carbon::createFromFormat('Y-m-d H:i:s', $this->login_time)->isoFormat('YYYY-MM-DD h:mm A'),
            'region_name' => $this->staffRegion->name,
            'saved_time' => Carbon::createFromFormat('Y-m-d H:i:s', $this->login_time)->isoFormat('YYYY-MM-DD h:mm A'),
            'options' => $this->staff_image ? "<a class='btn btn-success btn-sm mr-1' onClick='staffAttendance(&quot;". $this->staff_image ."&quot;)'><i class='fa fa-user'></i></a>" :
                                                '<p>-</p>'
        ];
    }
}
