<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitResourceRequirements extends Model
{
  protected $fillable = [''];
  protected $table = 'unit_resource_requirements';

  public function position(){
    return $this->belongsTo('App\Models\Positions', 'position_id');    
  }

  public function unit(){
    return $this->belongsTo('App\Models\Units', 'unit_id');    
  }
}
