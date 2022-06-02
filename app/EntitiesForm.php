<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntitiesForm extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_title','inputs', 'user_id'
    ];

    public function formCreator(){
        return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }

    public function entitiesFormData(){
    	return $this->hasMany('App\EntitiesFormData', 'entities_form_id');
    }
    

    // for assigning staffs entity tracking form
    public function staffs(){
    	return $this->belongsToMany('App\User', 'entities_form_user', 'entities_form_id', 'user_id')->withPivot([
            'assigner_id', 'entity_visit_count'
        ])
        ->withTrashed()
        ->withTimestamps();
    }

    public function company(){
    	return $this->belongsToMany('App\User', 'entities_form_user', 'entities_form_id', 'assigner_id')->withTrashed();
    }

    // admin sending entity tracking form to super admin, there is only one super admin so the relation is actually belongs to
    public function submitSuperAdmins(){
    	return $this->belongsToMany('App\User', 'entities_form_super_admin', 'entities_form_id', 'super_admin_id');
    }

    // linking client when creating new entity tracking form, there is only one client  
    public function clients(){
    	return $this->belongsToMany('App\Company', 'client_entities_form', 'entities_form_id', 'client_id');
    }

    public function entities(){
        return $this->belongsTo('App\EntitiesFormData', 'entity_id');
    }

    public function formFiller(){
        return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }
    public function companies(){
        return $this->belongsToMany('App\EntitiesForm', 'company_entities')->withTimestamps();
    }
}
