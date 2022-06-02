<?php

namespace App;

use App\Models\ReportData;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','phone_number','address','profile_image', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function scopeActive($query){
        return $query->whereActive(1);
    }
    public function scopeDeactive($query){
        return $query->whereActive(0);
    }
    public function companies(){
        return $this->belongsToMany(Company::class,'company_user')->withTimestamps();
    }
    //staff association to admin 
    public function users(){
        return $this->belongsToMany(User::class, 'creator_user', 'user_id', 'staff_id')
        ->withTimestamps();
    }
    public function creators(){
        return $this->belongsToMany(User::class, 'creator_user','staff_id','user_id')
        ->withTimestamps();
    }
    //admin and his staffs home page theme
    public function theme(){
        return $this->hasOne(Theme::class);
    }
    //staff association to admin 
    public function fieldstaffs(){
        return $this->belongsToMany(User::class, 'associate_user', 'user_id', 'staff_id')
        ->withPivot('staff_status')
        ->withTimestamps();
    }
    public function bosses(){
        return $this->belongsToMany(User::class, 'associate_user','staff_id','user_id')
        ->withPivot('staff_status')
        ->withTimestamps();
    }

    public function entitiesForms(){
    	return $this->hasMany('App\EntitiesForm', 'user_id');
    }

    public function entitiesFormData(){
    	return $this->hasMany('App\EntitiesFormData', 'user_id');
    }

    public function assignedEntitiesForms(){
    	return $this->belongsToMany('App\EntitiesForm', 'entities_form_user', 'user_id', 'entities_form_id')->withPivot([
            'assigner_id', 'entity_visit_count'
        ]);
    }
    public function formAssigned(){
        return $this->belongsToMany('App\EntitiesForm', 'entities_form_user', 'user_id', 'entities_form_id')
            ->withPivot('created_at');
    }
    
    public function regions(){
        return $this->belongsToMany(Region::class,'region_user')
        ->withPivot('region_name')
        ->withTimestamps();
    }

    public function submittedEntitiesForms(){
    	return $this->belongsToMany('App\EntitiesForm', 'entities_form_super_admin', 'super_admin_id', 'entities_form_id');
    }

    public function regionsList(){
        return $this->belongsToMany(Region::class,'region_user');
    }
    public function regionalStaff(){
        return $this->belongsToMany(User::class, 'associate_user', 'user_id', 'staff_id');
    }
    public function supervisorStaff(){
        return $this->belongsToMany(User::class, 'associate_user', 'user_id', 'staff_id');
    }
    public function assignStaffs(){
        return $this->belongsToMany('App\User', 'fieldstaffs_supervisors','supervisor_id','fieldstaff_id')->withPivot('company_id');
    }
    public function supervisorStaffs(){
        return $this->belongsToMany('App\User', 'fieldstaffs_supervisors','supervisor_id','fieldstaff_id')->withPivot('company_id');
    }
    public function staffSupervisors(){
        return $this->belongsToMany('App\User', 'fieldstaffs_supervisors','fieldstaff_id','supervisor_id')->withPivot('company_id');
    }
    public function reportData(){
        return $this->hasMany('App\Models\ReportData', 'user_id');
    }
}
