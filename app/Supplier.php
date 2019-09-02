<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table= 'suppliers';
    protected $fillable= ['id','contact','contact_telephone'];
    
    public $timestamps=false;

    public function person()
    {
        return $this->belongsTo('App\Person');
    }
}
