<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entitygroup extends Model
{
    protected $fillable = [
        'group_name', 'creator_id', 'entity_ids'
    ];

    protected $casts = [
        'entity_ids' => 'array'
    ];

    public function entityform(){
        return $this->hasMany('App\EntitiesFormData','id');
    }
}
