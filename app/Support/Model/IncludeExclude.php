<?php

namespace App\Support\Model;

/**
 * Trait IncludeExclude
 *
 * @package App\Support\Model
 */
trait IncludeExclude
{
    /**
     * @var null
     */
    public $includeIds = null;
    
    /**
     * @var null
     */
    public $excludeIds = null;
    
    /**
     * Scope: Include.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesIncludeIds($query)
    {
        if (is_array($this->includeIds)) {
            return $query->whereIn($this->table.'.id', empty($this->includeIds) ? [''] : $this->includeIds );
        }
    }
    
    /**
     * Scope: Exclude.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesExcludeIds($query)
    {
        if (is_array($this->excludeIds)) {
            return $query->whereNotIn($this->table.'.id', empty($this->excludeIds) ? [''] : $this->excludeIds );
        }
    }
}