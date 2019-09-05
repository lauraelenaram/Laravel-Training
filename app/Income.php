<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable= 
    [
        'supplier_id',
        'user_id',
        'voucher_type',
        'voucher_serie',
        'voucher_number',
        'date_hour',
        'tax',
        'total',
        'status'
    ];

    public function user()
    {
        return $thid->belongsTo('App\User');
    }

    public function supplier()
    {
        return $thid->belongsTo('App\Supplier');
    }
}
