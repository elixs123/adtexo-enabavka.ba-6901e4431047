<?php

namespace App\Support\Model;

/**
 * Trait Contract
 *
 * @package App\Support\Model
 */
trait Contract
{
    /**
     * @var null|string|array
     */
    public $contractId = null;
    
    /**
     * Scope: Contract.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesContract($query)
    {
        if (is_array($this->contractId)) {
            $query->whereIn('contract_id', empty($this->contractId) ? [''] : $this->contractId);
        } else if (is_numeric($this->contractId) && $this->contractId) {
            $query->where('contract_id', $this->contractId);
        }
    
        return $query;
    }
    
    /**
     * Relation: Contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rContract()
    {
        return $this->belongsTo(\App\Contract::class, 'contract_id', 'id');
    }
}
