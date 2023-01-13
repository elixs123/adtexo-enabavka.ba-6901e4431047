<?php namespace App;

use App\Support\Model\Country;
use App\Support\Model\Search;
use App\Support\Model\Status;

class Stock extends BaseModel
{
    use Status, Search, Country;

    protected $guarded = [];

    /**
     * Get brands
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sStatus()
            ->sSearch()
            ->sCountry()
            ->orderBy('country_id', 'asc')
            ->orderBy('name', 'asc')
            ->sPaginate();
    }
    
    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        if ($this->exists) {
            return $this->name.' ('.strtoupper($this->country_id).')';
        }
        
        return '';
    }
}
