<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffLocation extends Model
{
    protected $fillable = [
        'lat', 'lng', 'staff_id', 'company_id'
    ];
    public function staffDetail(){
        return $this->belongsTo('App\User', 'staff_id');
    }
}
