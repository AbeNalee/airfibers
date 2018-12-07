<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name', 'description', 'amount',
    ];
    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function vouchers()
    {
        return $this->hasMany('App\Voucher');
    }
}
