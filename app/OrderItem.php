<?php

namespace App;

use App\Support\Model\OrderHelper;

/**
 * Class OrderItem
 *
 * @package App
 */
class OrderItem extends BaseModel
{
    use OrderHelper;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_items';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'position',
        'product_code',
        'name',
        'quantity',
        'price',
        'discount_1',
        'discount_2',
        'discount_3',
        'discount_total',
        'total',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_id' => 'integer',
        'position' => 'integer',
        'quantity' => 'float',
        'price' => 'float',
        'discount_1' => 'float',
        'discount_2' => 'float',
        'discount_3' => 'float',
        'discount_total' => 'float',
        'total' => 'float',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'uid',
    ];
}
