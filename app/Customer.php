<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'phone_number',
    ];

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function device()
    {
        return $this->hasMany('App\MacAddress');
    }

    public function vouchers()
    {
        return $this->hasManyThrough('App\Voucher', 'App\MacAddress', 'customer_id', 'used_by', 'id', 'mac');
    }
}
