<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'phone_number', 'package_id', 'checkout_req_id', 'ap_mac', 'granted','customer_id', 'payment_method',
        'status', 'client_mac','merchant_req_id','amount', 'mpesa_id'
    ];

    public function package()
    {
        return $this->belongsTo('App\Package');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'phone_number', 'phone_number');
    }

    public function voucher()
    {
        return $this->hasOne('App\Voucher');
    }

    public function mpesa()
    {
        return $this->hasOne('App\Mpesa');
    }

    public function AccessPoint()
    {
        return $this->belongsTo('App\AccessPoint', 'ap_mac', 'mac_address');
    }
}
