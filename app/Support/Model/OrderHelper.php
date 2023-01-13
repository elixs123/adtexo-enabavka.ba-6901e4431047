<?php

namespace App\Support\Model;

/**
 * Trait OrderHelper
 *
 * @package App\Support\Model
 */
trait OrderHelper
{
    /**
     * @var null|string|array
     */
    public $orderId = null;
    
    /**
     * Scope: Order.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesOrder($query)
    {
        if (is_array($this->orderId)) {
            $query->whereIn('order_id', empty($this->orderId) ? [''] : $this->orderId);
        } else if (is_numeric($this->orderId) && $this->orderId) {
            $query->where('order_id', $this->orderId);
        }
    
        return $query;
    }
    
    /**
     * Relation: Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rOrder()
    {
        return $this->belongsTo(\App\Order::class, 'order_id', 'id');
    }
}
