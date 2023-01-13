<?php

namespace App;

use App\Support\Model\Client;
use App\Support\Model\DocumentHelper;

/**
 * Class Order
 *
 * @package App
 */
class Order extends BaseModel
{
    use Client, DocumentHelper;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_id',
        'name',
        'client_id',
        'client_data',
        'location_id',
        'location_data',
        'stock_name',
        'subtotal',
        'discount',
        'tax',
        'total',
        'status',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'document_id' => 'integer',
        'client_id' => 'integer',
        'client_data' => 'array',
        'location_id' => 'integer',
        'location_data' => 'array',
        'subtotal' => 'float',
        'discount' => 'float',
        'tax' => 'float',
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
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
