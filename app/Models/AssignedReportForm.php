<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedReportForm extends Model
{
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    protected $guarded = [];
}
