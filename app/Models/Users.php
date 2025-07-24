<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
// use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends EloquentUser implements AuthenticatableContract
{
  use Notifiable,Authenticatable;
  
  protected $fillable =[
      	'email',
      	'password',
        'first_name',
        'last_name',
        'phone',
        'permissions',
      ];

    protected $hidden = [
      'password',
      'remember_token',
    ];
    protected $appends = ['nama','nama_role','role_id','slug'];

    // public function roles()
    // {
    //     return $this->belongsToMany('App\Models\Roles','role_users','user_id','role_id');
    // }

    public function getRoleUser(){
      return $this->belongsTo('App\Models\RoleUsers', 'id','user_id');    
    }

    public function getRole(){
      return $this->belongsTo('App\Models\Roles', 'role_id');    
    }

    public function getPlacements()
    {
        return $this->hasMany(UserPlacements::class, 'user_id', 'id');
    }

    public function latestPlacement()
    {
        return $this->hasOne(UserPlacements::class, 'user_id')->latestOfMany();
    }

    public function getUnits()
    {
        return $this->belongsTo('App\Models\Units', 'unit_id');    
    }

    public function getNamaRoleAttribute()
    {
        return $this->getRoleUser->getRole->name;
    }

    public function getSlugAttribute()
    {
        return $this->getRoleUser->getRole->slug;
    }

    public function getRoleIdAttribute()
    {
        return $this->getRoleUser->getRole->id;
    }

    public function getNamaAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function uploadedDocuments()
    {
        return $this->hasMany(ApsDocuments::class, 'uploaded_by');
    }

    // public function placement()
    // {
    //     return $this->hasMany(UserPlacements::class, 'user_id');
    // }

    // public function roles()
    // {
    //     return $this->belongsToMany(Roles::class, 'user_roles', 'user_id', 'role_id');
    // }
}
