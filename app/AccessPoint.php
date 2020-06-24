<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessPoint extends Model
{
    protected $fillable = [
        'name', 'mac_address'
    ];

    public function payments()
    {
        return $this->hasMany('App\Payment', 'ap_mac', 'mac_address');
    }

    public function voucher()
    {
        return $this->hasMany('App\Voucher', 'used_at', 'mac_address');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
