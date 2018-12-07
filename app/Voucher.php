<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
      'voucher_code', 'package_id', 'payment_id', 'duration',
    ];

    public function package()
    {
        return $this->belongsTo('App\Package');
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
