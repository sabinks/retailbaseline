<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'description','company_id'
    ];
}
