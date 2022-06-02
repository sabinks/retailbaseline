<?php

namespace App\Models;

use App\User;
use App\Region;
use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    protected $fillable = ['user_id','region_id','staff_image','attendance_type', 'attendance_detail', 'from_date', 'to_date', 'login_time', 'remark', 'lat', 'lng'];

    public function staffDetail(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function staffRegion(){
        return $this->belongsTo(Region::class, 'region_id');
    }
}
