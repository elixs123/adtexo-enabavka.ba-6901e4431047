<?php

namespace App\Support\Model;

/**
 * Trait Search
 *
 * @package App\Support\Model
 */
trait Search
{
    /**
     * @var string
     */
    public $keywords = null;
    
    /**
     * Scope: Search.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesSearch($query)
    {
        if($this->isKeywordsValid()) {
            return $query->where($this->searchBy(), 'like', '%'.$this->keywords.'%');
        }
    }
    
    /**
     * Is keywords valid.
     *
     * @return bool|string
     */
    protected function isKeywordsValid()
    {
        if (($this->keywords != null) && (strlen($this->keywords) >= 2)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Search by.
     *
     * @return string
     */
    protected function searchBy()
    {
        return 'name';
    }
}