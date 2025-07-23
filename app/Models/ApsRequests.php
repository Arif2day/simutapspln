<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ApsDocuments;

class ApsRequests extends Model
{
  protected $fillable = [''];
  protected $table = 'aps_requests';
  
  public function user(){
      return $this->belongsTo('App\Models\Users', 'user_id');    
  }
  public function unitTo(){
      return $this->belongsTo('App\Models\Units','unit_id_to');
  }
  public function documents()
  {
      return $this->hasMany(ApsDocuments::class, 'aps_request_id');
  }
  public function unitFrom()
  {
      return $this->belongsTo(Units::class, 'unit_id_from');
  }

  public function positionTo()
  {
      return $this->belongsTo(Positions::class, 'position_id_to');
  }
  public function positionFrom()
  {
      return $this->belongsTo(Positions::class, 'position_id_from');
  }
}
