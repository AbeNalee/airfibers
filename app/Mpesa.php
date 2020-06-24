<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mpesa extends Model
{
    protected $fillable=[
      'trans_type', 'tran_id', 'trans_time', 'trans_amount', 'bill_ref', 'invoice_number', 'account_bal', 'third_party',
      'msisdn', 'first_name', 'last_name',
    ];

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
