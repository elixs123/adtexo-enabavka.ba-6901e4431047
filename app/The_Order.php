<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class The_Order extends Model
{
    protected $table = 'tHE_Order';
    public $timestamps = false;

    public function subject(){
        return $this->belongsTo(Subject::class, 'acSubject', 'acSubject');
    }

    public function items(){
        return $this->hasMany(The_OrderItem::class, 'orderNumber', 'orderNumber');
    }
}
