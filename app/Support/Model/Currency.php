<?php

namespace App\Support\Model;

/**
 * Trait Currency
 *
 * @package App\Support\Model
 */
trait Currency
{
    /**
     * Relation: Country.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rCurrency()
    {
        return $this->belongsTo(\App\CodeBook::class, 'currency', 'code');
    }
}
