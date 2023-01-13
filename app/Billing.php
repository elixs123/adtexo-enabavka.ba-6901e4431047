<?php

namespace App;

use App\Support\Model\Country;
use App\Support\Model\DateFromToHelper;
use App\Support\Model\FundSourceHelper;

/**
 * Class Billing
 *
 * @package App
 */
class Billing extends BaseModel
{
    use Country, FundSourceHelper, DateFromToHelper;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'billings';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country',
        'country_id',
        'fund_source',
        'fund_source_id',
        'kif',
        'payed',
        'date_of_payment',
        'person_id',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'payed' => 'float',
        'date_of_payment' => 'date',
        'person_id' => 'integer',
    ];
    
    /**
     * @var null|string|array
     */
    public $kifValue = null;
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rDemand()
    {
        return $this->belongsTo(Demand::class, 'kif', 'kif');
    }
    
    /**
     * Return list of clients.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sCountry()
            ->sFundSource()
            ->sKifValue()
            ->sDateFromTo()
            ->sPaginate();
    }
    
    /**
     * Scope: FundSource.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesKifValue($query)
    {
        if (is_array($this->kifValue) && isset($this->kifValue[0])) {
            $query->whereIn('kif', $this->kifValue);
        } else if (!is_null($this->kifValue) && $this->kifValue) {
            $query->where('kif', $this->kifValue);
        }
        
        return $query;
    }
}
