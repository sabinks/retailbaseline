<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $guarded  = [];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    public function users(){
        return $this->belongsToMany(User::class,'region_user')
        ->withPivot('region_name')
        ->withTrashed()
        ->withTimestamps();
    }
    public function superAdminStaffs(){
        $user = \Auth::user();
        $company = $user->companies()->first();
        $staff= $company->users()->role('Field Staff')->get()->pluck('id')->toArray();
        $staff_list =  $this->belongsToMany(User::class,'region_user')
                    ->whereIn('user_id', $staff)
                    ->role('Field Staff');

        return $staff_list;
    }
    public function adminStaffs(){
        $user = \Auth::user();
        $company = $user->companies()->first();
        $staff= $company->users()->role('Field Staff')->get()->pluck('id')->toArray();
        $staff_list =  $this->belongsToMany(User::class,'region_user')
                    ->whereIn('user_id', $staff)
                    ->role('Field Staff');

        return $staff_list;
    }

    public function entitiesFormData(){
    	return $this->hasMany('App\EntitiesFormData', 'region_id');
    }
}
