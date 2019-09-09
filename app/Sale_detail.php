<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale_detail extends Model
{
    protected $table= 'sales_details';
    protected $fillable= [
        'sale_id',
        'article_id',
        'quantity',
        'price',
        'discount'
    ];
    public $timestamps= false; 
}
