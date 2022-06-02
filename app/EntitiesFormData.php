<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntitiesFormData extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status','name', 'address', 'latitude', 'longitude', 'image', 'input_datas', 'filled_date', 'assigned_date', 'user_id', 'entities_form_id', 'region_id'
    ];

    protected $table = 'entities_form_datas';
    protected $casts = [
        'filled_date' => 'datetime:Y-m-d',
        'assigned_date' => 'datetime:Y-m-d',
    ];

    public function scopeFilled($query){
        return $query->whereStatus(1);
    }
    public function scopeApproved($query){
        return $query->whereStatus(2);
    }
    public function scopeRejected($query){
        return $query->whereStatus(3);
    }
    
    public function entitiesForm(){
        return $this->belongsTo('App\EntitiesForm', 'entities_form_id');
    }

    public function formFiller(){
        return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }

    // linking client when creating new entity tracking form data, there is only one client which is obtained from the entity form  
    public function clients(){
        return $this->belongsToMany('App\Company', 'client_entities_form_data', 'entities_form_data_id', 'client_id');
    }

    public function region(){
        return $this->belongsTo('App\Region', 'region_id');
    }

    public function entities(){
        return $this->belongsTo('App\EntitiesFormData', 'entity_id');
    }
}
