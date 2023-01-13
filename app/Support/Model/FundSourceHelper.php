<?php

namespace App\Support\Model;

/**
 * Trait FundSourceHelper
 *
 * @package App\Support\Model
 */
trait FundSourceHelper
{
    /**
     * @var null|string|array
     */
    public $fundSourceId = null;
    
    /**
     * Scope: FundSource.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesFundSource($query)
    {
        if (is_array($this->fundSourceId) && isset($this->fundSourceId[0])) {
            $query->whereIn('fund_source_id', $this->fundSourceId);
        } else if (!is_null($this->fundSourceId) && $this->fundSourceId) {
            $query->where('fund_source_id', $this->fundSourceId);
        }
    
        return $query;
    }
    
    /**
     * Relation: FundSource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rFundSource()
    {
        return $this->belongsTo(\App\CodeBook::class, 'fund_source_id', 'code');
    }
}
