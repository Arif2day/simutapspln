<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Users\EloquentUser;

class Users extends EloquentUser
{
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
    protected $appends = ['nama','nama_role','role_id'];

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

    public function getNamaRoleAttribute()
    {
        return $this->getRoleUser->getRole->name;
    }

    public function getRoleIdAttribute()
    {
        return $this->getRoleUser->getRole->id;
    }

    public function getNamaAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

}
