<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportImage extends Model
{
    protected $fillable = [
        'reportdata_id', 'form_field_name','form_label','image_name'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
