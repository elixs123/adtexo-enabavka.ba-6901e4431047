<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class The_OrderItem extends Model
{
    protected $table = 'tHE_OrderItem';
    public $timestamps = false;

    public function items(){
        return $this->belongsTo(Product::class, 'acIdent', 'acIdent');
    }
}
