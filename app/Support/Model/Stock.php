<?php

namespace App\Support\Model;

/**
 * Trait Stock
 *
 * @package App\Support\Model
 */
trait Stock
{
    /**
     * @var null|string|array
     */
    public $stockId = null;
    
    /**
     * Scope: Stock.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesStock($query)
    {
        if (is_array($this->stockId)) {
            $query->whereIn('stock_id', empty($this->stockId) ? [''] : $this->stockId);
        } else if (is_numeric($this->stockId) && $this->stockId) {
            $query->where('stock_id', $this->stockId);
        }
    
        return $query;
    }
    
    /**
     * Scope: Country.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesStockCountry($query)
    {
        if (is_null($this->countryId)) {
            return $query;
        }
        
        return $query->whereHas('rStock.rCountry', function($query) {
            if (is_array($this->countryId) && isset($this->countryId[0])) {
                $query->whereIn('country_id', $this->countryId);
            } else if (!is_null($this->countryId) && $this->countryId) {
                $query->where('country_id', $this->countryId);
            }
        });
    }
    
    /**
     * Relation: Stock.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rStock()
    {
        return $this->belongsTo(\App\Stock::class, 'stock_id', 'id');
    }
}
