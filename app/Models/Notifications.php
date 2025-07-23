<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    public $incrementing = false; // karena id-nya UUID
    protected $keyType = 'string';
    
    protected $table = 'notifications';

    // protected $fillable = [
    //     'id', 'type', 'notifiable_type', 'notifiable_id', 'data', 'read_at'
    // ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    protected $guarded = [];
}
