<?php

namespace App\Support\Model;

/**
 * Trait Person
 *
 * @package App\Support\Model
 */
trait Person
{
    /**
     * @var null|string|array
     */
    public $personId = null;
    
    /**
     * Scope: Person.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesPerson($query)
    {
        if (is_array($this->personId) && isset($this->personId[0])) {
            $query->whereIn('person_id', $this->personId);
        } else if (!is_null($this->personId) && $this->personId) {
            $query->where('person_id', $this->personId);
        }
    
        return $query;
    }
    
    /**
     * Relation: Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rPerson()
    {
        return $this->belongsTo(\App\Person::class, 'person_id', 'id');
    }
}