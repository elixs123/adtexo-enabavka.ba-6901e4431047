<?php

namespace App;

use App\Support\Model\Client;
use App\Support\Model\Country;
use App\Support\Model\DateFromToHelper;
use App\Support\Model\DocumentHelper;
use App\Support\Model\Person;

/**
 * Class Demand
 *
 * @package App
 */
class Demand extends BaseModel
{
    use Country, DocumentHelper, Person, Client, DateFromToHelper;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demands';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country',
        'country_id',
        'kif',
        'binding_document',
        'document',
        'document_id',
        'salesman_person',
        'person_id',
        'client',
        'client_id',
        'date_of_document',
        'date_of_payment',
        'amount',
        'payed',
        'debt',
        'overdue_days',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'document_id' => 'integer',
        'person_id' => 'integer',
        'client_id' => 'integer',
        'date_of_document' => 'date',
        'date_of_payment' => 'date',
        'amount' => 'float',
        'payed' => 'float',
        'debt' => 'float',
        'overdue_days' => 'integer',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rBillings()
    {
        return $this->hasMany(Billing::class, 'kif', 'kif');
    }
    
    /**
     * Return list of clients.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sCountry()
            ->sDocument()
            ->sPerson()
            ->sClient()
            ->sDateFromTo()
            ->sPaginate();
    }
}
