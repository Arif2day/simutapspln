<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ApsRequests;
use App\Models\ApsApprovals;
use App\Models\Users;

class ApsDocuments extends Model
{
  protected $fillable = [
      'aps_request_id',
      'aps_approval_id',
      'document_type',
      'file_path',
      'uploaded_by',
      'uploaded_at',
      'notes',
  ];

  protected $dates = ['uploaded_at'];

  public function request()
  {
      return $this->belongsTo(ApsRequests::class, 'aps_request_id');
  }

  public function approval()
  {
      return $this->belongsTo(ApsApprovals::class, 'aps_approval_id');
  }

  public function uploader()
  {
      return $this->belongsTo(Users::class, 'uploaded_by');
  }
}
