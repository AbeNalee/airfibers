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
}
