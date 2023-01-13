<?php

namespace App\Support\Model;

/**
 * Trait Action
 *
 * @package App\Support\Model
 */
trait Action
{
    /**
     * @var null|string|array
     */
    public $actionId = null;
    
    /**
     * Scope: Action.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesAction($query)
    {
        if (is_array($this->actionId)) {
            $query->whereIn('action_id', empty($this->actionId) ? [''] : $this->actionId);
        } else if (is_numeric($this->actionId) && $this->actionId) {
            $query->where('action_id', $this->actionId);
        }
    
        return $query;
    }
    
    /**
     * Relation: Action.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rAction()
    {
        return $this->belongsTo(\App\Action::class, 'action_id', 'id');
    }
}
