<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlacements extends Model
{
    use HasFactory;
    protected $fillable = [''];
    protected $table = 'user_placements';

    public function getUser(){
        return $this->belongsTo('App\Models\Users', 'user_id');    
    }

    public function getPosition(){
        return $this->belongsTo('App\Models\Positions', 'position_id');    
    }

    public function getUnit(){
        return $this->belongsTo('App\Models\Units', 'unit_id');    
    }
}
