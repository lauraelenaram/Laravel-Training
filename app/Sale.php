<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable= [
        'client_id',
        'user_id',
        'voucher_type',
        'voucher_number',
        'voucher_serie',
        'date_hour',
        'tax',
        'total',
        'status'
    ];
}
