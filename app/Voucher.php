<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
      'voucher_code', 'package_id', 'payment_id', 'duration', 'used', 'used_by'
    ];

    public function package()
    {
        return $this->belongsTo('App\Package');
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }

    public function device()
    {
        return $this->belongsTo('App\MacAddress', 'used_by', 'mac');
    }
}
