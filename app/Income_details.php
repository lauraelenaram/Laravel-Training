<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income_details extends Model
{
    protected $table= 'income_details';
    protected $fillable= 
    [
        'income_id',
        'article_id',
        'quantity',
        'price'
    ]; 

    public $timestamps= false;
}
