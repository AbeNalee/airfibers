<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'phone_number', 'package_id', 'transaction_ref',
        'pesapal_tracking_id','voucher_code', 'payment_method', 'status', 'tracking_id','merchant_ref','amount',
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
}
