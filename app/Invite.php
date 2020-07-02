<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'invite_code'
    ];

    protected $hidden = [
        'user_id', 'inviter_id', 'isActive'
    ];

    public function getInviter()
    {
        return $this->belongsTo('App\User', 'inviter_id');
    }
}
