<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $hidden = ['created_at', 'updated_at'];

    public function interests() {
        return $this->hasMany('App\ProductInterest');
    }

    public function getActiveAttribute($value) {
        return (bool) $value;
    }

}