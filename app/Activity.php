<?php

namespace App;

/**
 * Class Activity
 *
 * @package App
 */
class Activity extends BaseModel
{
    protected $revisionEnabled = false;	
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activities';
	
    /**
     * Start date
     * @var date
     */
    public $dateStart = null;

    /**
     * End date
     * @var date
     */
    public $dateEnd = null;	
    
    /**
     * Relation: User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }
	
    /**
     * Scope: User id.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeUserId($query)
    {
        if (is_array($this->userId) && count($this->userId)) {
            $query->whereIn('user_id', $this->userId);
        } else if (is_numeric($this->userId)) {
            $query->where('user_id', $this->userId);
        }
        
        return $query;
    }
	
    /**
     * Filter by date
     * @return Resource
     */
    public function scopeDate($query)
    {
        if ($this->dateStart != null)
        {
            $query->whereDate('created_at', '>=', $this->dateStart);
        }

        if ($this->dateEnd != null)
        {
            $query->whereDate('created_at', '<=', $this->dateEnd);
        }

        return $query;
    }

    /**
     * Get all.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return self::userId()
            ->date()
            ->with(['rUser'])
            ->orderBy('created_at', 'desc')
            ->sPaginate();
    }	
	
	
    /**
     * Get model slug name
     *
     * @param  string  $value
     * @return string
     */
    public function getModelSlugAttribute($value)
    {
		list($action, $name) = explode('_', $this->name);
		
        return $name;
    }	

    /**
     * Get model slug name
     *
     * @param  string  $value
     * @return string
     */
    public function getActionNameAttribute($value)
    {
		list($action, $name) = explode('_', $this->name);
		
        return $action;
    }	
}