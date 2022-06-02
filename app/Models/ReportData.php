<?php

namespace App\Models;

use App\Models\Report;
use App\EntitiesFormData;
use Illuminate\Database\Eloquent\Model;

class ReportData extends Model
{
    public function scopeApproved($query){
        return $query->whereStatus(3);
    }
    public function scopeRejected($query){
        return $query->whereStatus(4);
    }
    public function scopeAssigned($query){
        return $query->whereStatus(1);
    }
    public function scopePending($query){
        return $query->whereStatus(2);
    }
    public function reportEntityData(){
        return $this->belongsTo(EntitiesFormData::class, 'entity_id');
    }
    public function reportForm(){
        return $this->belongsTo(Report::class, 'report_id');
    }
    public function region(){
        return $this->belongsTo('App\Region');
    }
    public function report(){
        return $this->belongsTo('App\Models\Report');
    }
    public function staffDetail(){
        return $this->belongsTo('App\User', 'staff_id')->withTrashed();
    }
    public function entities(){
        return $this->belongsTo('App\EntitiesFormData', 'entity_id');
    }
    public function fieldStaff(){
        return $this->belongsTo('App\User', 'staff_id');
    }
    public function reportEntityGroup(){
        return $this->belongsTo('App\Entitygroup', 'entitygroup_id');
    }
    public function reportImages(){
        return $this->hasMany('App\Models\ReportImage', 'reportdata_id');
    }
    public function company(){
        return $this->belongsToMany('App\Company', 'company_user', 'user_id', 'company_id');
    }
}
