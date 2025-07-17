<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUsers extends Model
{
  protected $fillable = [''];
  protected $table = 'role_users';

  public function getRole(){
    return $this->belongsTo('App\Models\Roles', 'role_id');    
  }
}
