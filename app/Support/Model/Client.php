<?php

namespace App\Support\Model;

/**
 * Trait Client
 *
 * @package App\Support\Model
 */
trait Client
{
    /**
     * @var null|string|array
     */
    public $clientId = null;
    
    /**
     * Scope: Client.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesClient($query)
    {
        if (is_array($this->clientId)) {
            $query->whereIn('client_id', empty($this->clientId) ? [''] : $this->clientId);
        } else if (is_numeric($this->clientId) && $this->clientId) {
            $query->where('client_id', $this->clientId);
        }
    
        return $query;
    }
    
    /**
     * Relation: Client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rClient()
    {
        return $this->belongsTo(\App\Client::class, 'client_id', 'id');
    }
}
