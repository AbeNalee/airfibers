<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MacAddress extends Model
{
    protected $fillable = [
        'mac', 'ap_mac',
    ];
}
