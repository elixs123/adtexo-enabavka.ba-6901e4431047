<?php

namespace App;

use App\Support\Model\Country;
use App\Support\Model\IncludeExclude;
use App\Support\Model\Search;
use App\Support\Model\Status;
use App\Support\Model\Stock;
use App\Support\Model\Type;

/**
 * Class City
 *
 * @package App
 */
class City extends BaseModel
{
    use IncludeExclude, Search, Status, Country, RecordActivity;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id',
        'postal_code',
        'name',
        'status',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'uid'
    ];
    
    /**
     * Return list of clients.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sSearch()
            ->sStatus()
            ->sCountry()
            ->sIncludeIds()
            ->sExcludeIds()
            ->sPaginate();
    }
    
    /**
     * Scope: Search.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesSearch($query)
    {
        if($this->isKeywordsValid()) {
            return $query->where('name', 'like', '%'.$this->keywords.'%')
                ->orWhere('postal_code', 'like', '%'.$this->keywords.'%');
        }
    }

    /**
     * @return string
     */
    public function getFullCityAttribute()
    {
        if ($this->exists) {
            return $this->name.' (' . $this->postal_code . ')';
        }
        
        return '';
    }
}
