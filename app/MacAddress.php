<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MacAddress extends Model
{
    protected $fillable = [
        'mac', 'customer_id', 'ap_mac'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function voucher()
    {
        return $this->hasMany('App\Voucher', 'used_by', 'mac');
    }

    public function owner()
    {
        return $this->belongsTo('App\Customer');
    }
}
