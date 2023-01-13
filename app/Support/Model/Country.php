<?php

namespace App\Support\Model;

/**
 * Trait Country
 *
 * @package App\Support\Model
 */
trait Country
{
    /**
     * @var null|string|array
     */
    public $countryId = null;
    
    /**
     * Scope: Country.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesCountry($query)
    {
        if (is_array($this->countryId) && isset($this->countryId[0])) {
            $query->whereIn('country_id', $this->countryId);
        } else if (!is_null($this->countryId) && $this->countryId) {
            $query->where('country_id', $this->countryId);
        }
    
        return $query;
    }
    
    /**
     * Relation: Country.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rCountry()
    {
        return $this->belongsTo(\App\CodeBook::class, 'country_id', 'code');
    }
}