<?php

namespace App\Support\Model;

/**
 * Trait Status
 *
 * @package App\Support\Model
 */
trait Status
{
    /**
     * @var null|string|array
     */
    public $statusId = null;
    
    /**
     * Scope: Status.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesStatus($query)
    {
        $column = is_null($this->table) ? 'status' : $this->table.'.status';
        
        if (is_array($this->statusId) && isset($this->statusId[0])) {
            $query->whereIn($column, $this->statusId);
        } else if (!is_null($this->statusId) && $this->statusId) {
            $query->where($column, $this->statusId);
        }
    
        return $query;
    }
    
    /**
     * Relation: Status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rStatus()
    {
        return $this->belongsTo(\App\CodeBook::class, 'status', 'code');
    }
    
    /**
     * Scope: Status.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeActive($query)
    {
        $this->statusId = 'active';
        
        return $this->scopesStatus($query);
    }
}
