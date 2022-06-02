<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function companies(){
        return $this->belongsToMany('App\Models\Report', 'company_reports')->withTimestamps();
    }

}
