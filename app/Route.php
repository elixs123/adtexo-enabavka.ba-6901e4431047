<?php

namespace App;

use App\Support\Model\Client;
use App\Support\Model\Person;

/**
 * Class Route
 *
 * @package App
 */
class Route extends BaseModel
{
    use Client, Person;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'routes';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id',
        'client_id',
        'week',
        'day',
        'rank',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'person_id' => 'integer',
        'client_id' => 'integer',
        'week' => 'integer',
        'day' => 'string',
        'rank' => 'integer',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'uid',
        'week_day',
    ];
    
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'rPerson',
        'rClient',
    ];
    
    /**
     * Rows per page / query.
     *
     * @var int
     */
    public $limit = null;
    
    /**
     * @var null|int|array
     */
    public $weekId = null;
    
    /**
     * @var null|string|array
     */
    public $dayId = null;
    
    /**
     * Return list of persons.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sPerson()
            ->sClient()
            ->sWeek()
            ->sDay()
            ->orderBy('rank', 'asc')
            ->sPaginate();
    }
    
    /**
     * Scope: Week.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesWeek($query)
    {
        if (is_array($this->weekId) && isset($this->weekId[0])) {
            $query->whereIn('week', $this->weekId);
        } else if (is_numeric($this->weekId) && $this->weekId) {
            $query->where('week', $this->weekId);
        }
        
        return $query;
    }
    
    /**
     * Scope: Day.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesDay($query)
    {
        if (is_array($this->dayId) && isset($this->dayId[0])) {
            $query->whereIn('day', $this->dayId);
        } else if (is_string($this->dayId) && $this->dayId) {
            $query->where('day', $this->dayId);
        }
        
        return $query;
    }
    
    /**
     * Attribute: Week Day
     * @return string
     */
    public function getWeekDayAttribute()
    {
        if ($this->exists) {
            return $this->week.'-'.$this->day;
        }
        
        return '-';
    }
}
