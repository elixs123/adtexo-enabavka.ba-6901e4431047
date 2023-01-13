<?php

namespace App;

use App\Support\Model\Search;
use App\Support\Model\Status;

/**
 * Class Permission
 *
 * @package App
 */
class Permission extends BaseModel
{
	use RecordActivity, Search, Status;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'label',
        'object',
        'status',
    ];
    
    /**
     * @var null|string|array
     */
    public $objectId = null;
    
    /**
     * A permission can be applied to roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    
    /**
     * Scope: Object.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesObject($query)
    {
        if (is_array($this->objectId) && isset($this->objectId[0])) {
            $query->whereIn('object', $this->objectId);
        } else if (!is_null($this->objectId) && $this->objectId) {
            $query->where('object', $this->objectId);
        }
        
        return $query;
    }
    
    /**
     * Return list of items
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sSearch()
            ->sObject()
            ->sStatus()
            ->orderBy('object', 'asc')
            ->orderBy('name', 'asc')
            ->sPaginate();
    }
    
    /**
     * Return list of available objects
     * @return array
    */
    public function getObjects()
    {
        return trans('permission.vars.objects');
    }
}
