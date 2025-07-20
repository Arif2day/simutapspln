<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ApsDocuments;

class ApsApprovals extends Model
{
  protected $fillable = [''];
  protected $table = 'aps_approvals';

  public function documents()
  {
      return $this->hasMany(ApsDocuments::class, 'aps_approval_id');
  }

  public function request(): BelongsTo
    {
        return $this->belongsTo(ApsRequests::class, 'aps_request_id');
    }

    // Jika approved_by adalah user_id (numeric), ganti jadi ini:
    public function approver(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'approved_by');
    }
}
