<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = ['name','document_type','document_number','address','telephone','email'];

    public function supplier()
    {
        return $this.hasOne('App\Supplier');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }
}


