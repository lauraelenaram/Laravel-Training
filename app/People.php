<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    protected $fillable= ['name','document_type','document_number','address','telephone','email'];
}
