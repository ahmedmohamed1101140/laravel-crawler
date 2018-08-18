<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function amounts(){
        return $this->hasMany(Amount::class,'prod_id','id');
    }
}
