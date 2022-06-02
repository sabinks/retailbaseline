<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name', 'company_phone_number','company_address','theme','webaddress','company_logo'
    ];
    public function users(){
        return $this->belongsToMany(User::class,'company_user');
    }

    public function entitiesFormData(){
        return $this->belongsToMany('App\EntitiesFormData', 'client_entities_form_data', 'client_id', 'entities_form_data_id');
    }

    public function entitiesForms(){
    	return $this->belongsToMany('App\EntitiesForm', 'client_entities_form', 'client_id', 'entities_form_id');
    }
    public function reportForm(){
        return $this->belongsToMany('App\Models\Report', 'company_reports')->withTimestamps();
    }
    public function entityForm(){
        return $this->belongsToMany('App\EntitiesForm', 'company_entities')->withTimestamps();
    }
}
