<?php

namespace App\Support\Model;

/**
 * Trait PaymentType
 *
 * @package App\Support\Model
 */
trait PaymentType
{
    /**
     * @var null|string|array
     */
    public $paymentTypeId = null;
    
    /**
     * Scope: Type.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesPaymentType($query)
    {
        if (is_array($this->paymentTypeId) && isset($this->paymentTypeId[0])) {
            $query->whereIn('payment_type', $this->paymentTypeId);
        } else if (!is_null($this->paymentTypeId) && $this->paymentTypeId) {
            $query->where('payment_type', $this->paymentTypeId);
        }
    
        return $query;
    }
    
    /**
     * Relation: Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rPaymentType()
    {
        return $this->belongsTo(\App\CodeBook::class, 'payment_type', 'code');
    }
}
