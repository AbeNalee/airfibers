<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function vouchers()
    {
        return $this->hasMany('App\Voucher');
    }

    public function accessPoints()
    {
        return $this->hasMany('App\AccessPoint');
    }

    public function agentMpesa()
    {
        return $this->hasMany('App/AgentMpesa', 'agent_id');
    }
}
