<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $guarded = ['owner_id', 'receiver_id'];

    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\User', 'receiver_id');
    }
}
