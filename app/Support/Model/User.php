<?php

namespace App\Support\Model;

/**
 * Trait User
 *
 * @package App\Support\Model
 */
trait User
{
    /**
     * @var null|string|array
     */
    public $userId = null;
    
    /**
     * Scope: User.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesUser($query)
    {
        if (is_array($this->userId) && isset($this->userId[0])) {
            $query->whereIn('user_id', $this->userId);
        } else if (!is_null($this->userId) && $this->userId) {
            $query->where('user_id', $this->userId);
        }
    
        return $query;
    }
    
    /**
     * Relation: User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rUser()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }
}