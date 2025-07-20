<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
  protected $fillable = [''];
  protected $table = 'units';

  public function getUnitType(){
    return $this->belongsTo('App\Models\UnitTypes', 'unit_type_id');    
  }
}
