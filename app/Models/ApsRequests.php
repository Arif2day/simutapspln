<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ApsDocuments;

class ApsRequests extends Model
{
  protected $fillable = [''];
  protected $table = 'aps_requests';

  public function documents()
  {
      return $this->hasMany(ApsDocuments::class, 'aps_request_id');
  }
}
