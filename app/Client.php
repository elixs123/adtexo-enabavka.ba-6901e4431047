<?php

namespace App;

use App\Support\Model\Country;
use App\Support\Model\IncludeExclude;
use App\Support\Model\PaymentType;
use App\Support\Model\PublicHashHelper;
use App\Support\Model\Search;
use App\Support\Model\Status;
use App\Support\Model\Stock;
use App\Support\Model\Type;

/**
 * Class Client
 *
 * @package App
 */
class Client extends BaseModel
{
    use IncludeExclude, Search, Status, Type, Country, Stock, RecordActivity, PublicHashHelper, PaymentType;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'type_id',
        'jib',
        'pib',
        'code',
        'luceed_uid',
        'name',
        'address',
        'city',
        'postal_code',
        'country_id',
        'is_location',
        'location_code',
        'location_name',
        'location_type_id',
        'category_id',
        'photo',
        'photo_contract',
        'latitude',
        'longitude',
        'phone',
        'client_person_id',
        'responsible_person_id',
        'payment_person_id',
        'salesman_person_id',
        'supervisor_person_id',
        'note',
        'payment_therms',
        'payment_period',
        'payment_type',
        'payment_discount',
        'discount_value1',
        'discount_value2',
        'stock_id',
        'lang_id',
        'allowed_limit_in',
        'allowed_limit_outside',
        'status',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'parent_id' => 'integer',
        'type_id' => 'string',
        'jib' => 'string',
        'pib' => 'string',
        'code' => 'string',
        'name' => 'string',
        'address' => 'string',
        'city' => 'string',
        'postal_code' => 'string',
        'country_id' => 'string',
        'is_location' => 'boolean',
        'location_code' => 'string',
        'location_name' => 'string',
        'location_type_id' => 'string',
        'category_id' => 'string',
        'photo' => 'string',
        'photo_contract' => 'string',
        'latitude' => 'float',
        'longitude' => 'float',
        'phone' => 'string',
        'client_person_id' => 'integer',
        'responsible_person_id' => 'integer',
        'payment_person_id' => 'integer',
        'salesman_person_id' => 'integer',
        'supervisor_person_id' => 'integer',
        'note' => 'string',
        'payment_period' => 'string',
        'payment_type' => 'string',
        'payment_discount' => 'float',
        'discount_value1' => 'float',
        'discount_value2' => 'float',
        'stock_id' => 'integer',
        'lang_id' => 'string',
        'allowed_limit_in' => 'float',
        'allowed_limit_outside' => 'float',
        'status' => 'string',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'uid',
        'is_headquarter',
        'full_name',
        'payment_period_in_days',
        'full_address',
    ];
    
    /**
     * @var null|string|array
     */
    public $parentId = null;
    
    /**
     * @var null|bool
     */
    public $isLocation = null;
    
    /**
     * @var null|int|array
     */
    public $stockId = null;
    
    /**
     * @var bool
     */
    public $onlyLocations = false;
    
    /**
     * @var null|string
     */
    public $personType = null;
    
    /**
     * @var null|int
     */
    public $personId = null;
    
    /**
     * Relation: Responsible person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rClientPerson()
    {
        return $this->belongsTo(\App\Person::class, 'client_person_id', 'id');
    }
    
    /**
     * Relation: Responsible person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rResponsiblePerson()
    {
        return $this->belongsTo(\App\Person::class, 'responsible_person_id', 'id');
    }
    
    /**
     * Relation: Payment person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rPaymentPerson()
    {
        return $this->belongsTo(\App\Person::class, 'payment_person_id', 'id');
    }
    
    /**
     * Relation: Salesman person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rSalesmanPerson()
    {
        return $this->belongsTo(\App\Person::class, 'salesman_person_id', 'id');
    }
    
    /**
     * Relation: Salesman person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rSupervisorPerson()
    {
        return $this->belongsTo(\App\Person::class, 'supervisor_person_id', 'id');
    }
    
    /**
     * Relation: Salesman person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rSalesmanPersons()
    {
        return $this->belongsToMany(Person::class, 'routes', 'client_id', 'person_id')->withPivot(['week', 'day', 'rank'])->withTimestamps();
    }
    
    /**
     * Relation: Routes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rRoutes()
    {
        return $this->hasMany(\App\Route::class, 'client_id', 'id');
    }
    
    /**
     * Relation: Payment period.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rPaymentPeriod()
    {
        return $this->belongsTo(\App\CodeBook::class, 'payment_period', 'code');
    }
    
    /**
     * Relation: Payment period.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rPaymentTherms()
    {
        return $this->belongsTo(\App\CodeBook::class, 'payment_therms', 'code');
    }
    
    /**
     * Relation: Categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rCategories()
    {
        return $this->belongsToMany('App\Category', 'client_category');
    }
    
    /**
     * Relation: Products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rProducts()
    {
        return $this->belongsToMany('App\Product', 'client_product');
    }
    
    /**
     * Relation: Locations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rLocations()
    {
        return $this->hasMany(Client::class, 'parent_id', 'id');
    }
    
    /**
     * Relation: Location type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rLocationType()
    {
        return $this->belongsTo(\App\CodeBook::class, 'location_type_id', 'code');
    }
    
    /**
     * Relation: Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rCategory()
    {
        return $this->belongsTo(\App\CodeBook::class, 'category_id', 'code');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rContract()
    {
        return $this->hasOne(Contract::class, 'client_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rHeadquarter()
    {
        return $this->belongsTo(Client::class, 'parent_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rActions()
    {
        return $this->belongsToMany(Action::class, 'client_actions', 'client_id', 'action_id');
    }
    
    /**
     * Return list of clients.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sParent()
            ->sType()
            ->sSearch()
            ->sStatus()
            ->sCountry()
            ->sLocation()
            ->sPerson()
            ->sStock()
            ->sPaymentType()
            ->sIncludeIds()
            ->sExcludeIds()
            ->sOrder()
            ->sPaginate();
    }
    
    /**
     * Scope: Order.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesOrder($query)
    {
        // if (is_array($this->includeIds) && !empty($this->includeIds)) {
        //     return $query->orderByRaw(DB::raw("FIND_IN_SET({$this->table}.id,'".implode(',', $this->includeIds)."')"));
        // }
        
        return $query->orderBy('name', 'asc');
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
                ->orWhere('location_name', 'like', '%'.$this->keywords.'%')
                ->orWhere('jib', 'like', '%'.$this->keywords.'%')
                ->orWhere('pib', 'like', '%'.$this->keywords.'%')
                ->orWhere('pib', 'like', '%'.$this->keywords.'%')
                ->orWhere('code', 'like', '%'.$this->keywords.'%')
                ->orWhere('location_code', 'like', '%'.$this->keywords.'%');
        }
    }
    
    /**
     * Scope: Parent.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesParent($query)
    {
        if ($this->onlyLocations === true) {
            $query->where('is_location', 1);
        } else if (is_array($this->parentId) && isset($this->parentId[0])) {
            $query->whereIn('parent_id', $this->parentId);
        } else if (!is_null($this->parentId) && $this->parentId) {
            $query->where('parent_id', $this->parentId);
        } else if (is_null($this->parentId) && ($this->onlyLocations === false)) {
            //$query->whereNull('parent_id');
        }
        
        return $query;
    }
    
    /**
     * Scope: Country.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesCountry($query)
    {
        if (is_array($this->countryId) && isset($this->countryId[0])) {
            $query->whereIn('country_id', $this->countryId);
        } else if (!is_null($this->countryId) && $this->countryId) {
            $query->where('country_id', $this->countryId);
        }
        
        return $query;
    }
    
    /**
     * Scope: Stock.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesStock($query)
    {
        if (is_array($this->stockId) && isset($this->stockId[0])) {
            $query->whereIn('stock_id', $this->stockId);
        } else if (!is_null($this->stockId) && $this->stockId) {
            $query->where('stock_id', $this->stockId);
        }
        
        return $query;
    }
    
    /**
     * Scope: Is location.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesLocation($query)
    {
        if ($this->isLocation === true) {
            $query->where('is_location', 1);
        } else if ($this->isLocation === false) {
            $query->where('is_location', 0);
        }
        
        return $query;
    }
    
    /**
     * Scope: Person.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesPerson($query)
    {
        if (is_null($this->personType) || is_null($this->personId)) {
            return $query;
        }
        
        if (is_string($this->personType) && is_numeric($this->personId)) {
            $query->where($this->table.'.'.$this->personType.'_id', $this->personId);
        }
        
        return $query;
    }
    
    /**
     * Client types.
     *
     * @param null $id
     * @return array|mixed
     */
    public function getTypes($id = null)
    {
        return $this->getCodeBookOptions('client_types', $id);
    }
    
    /**
     * Countries.
     *
     * @param null $id
     * @return array|mixed
     */
    public function getCountries($id = null)
    {
        return $this->getCodeBookOptions('countries', $id);
    }
    
    /**
     * Location types.
     *
     * @param null $id
     * @return array|mixed
     */
    public function getLocationTypes($id = null)
    {
        return $this->getCodeBookOptions('client_location_types', $id);
    }
    
    /**
     * Client categories.
     *
     * @param null $id
     * @return array|mixed
     */
    public function getCategories($id = null)
    {
        return $this->getCodeBookOptions('client_categories', $id);
    }
    
    /**
     * Payment periods.
     *
     * @param null $id
     * @return array|mixed
     */
    public function getPaymentPeriods($id = null)
    {
        return $this->getCodeBookOptions('payment_period', $id);
    }
    
    /**
     * Payment types.
     *
     * @param null $id
     * @return array|mixed
     */
    public function getPaymentTypes($id = null)
    {
        return $this->getCodeBookOptions('payment_type', $id);
    }
    
    /**
     * Payment therms.
     *
     * @param null $id
     * @return array|mixed
     */
    public function getPaymentTherms($id = null)
    {
        return $this->getCodeBookOptions('payment_therms', $id);
    }
    
    /**
     * Attribute: Is headquarter.
     *
     * @return bool
     */
    public function getIsHeadquarterAttribute()
    {
        if ($this->exists && is_null($this->parent_id)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Attribute: Full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        if ($this->is_headquarter) {
            return $this->name;
        }
        
        return $this->location_name;
    }
    
    /**
     * @return string
     */
    public function getFullAddressAttribute()
    {
        if ($this->exists) {
            return $this->address.', '.$this->city;
        }
        
        return '';
    }

    /**
     * @return string
     */
    public function getFullCityAttribute()
    {
        if ($this->exists) {
            return $this->city.' (' . $this->postal_code . ')';
        }
        
        return '';
    }
    
    /**
     * @return array
     */
    public function getClientTabs($parent_id)
    {
        $tabs = trans('client.vars.tabs');
        
        if (userIsSalesman() || userIsFocuser() || !is_null($parent_id)) {
            if (userIsSalesman()) {
                unset($tabs['categories'], $tabs['products'], $tabs['actions']);
            } else {
                unset($tabs['payment'], $tabs['categories'], $tabs['products'], $tabs['actions']);
            }
        }
        
        if (userIsFocuser()) {
            unset($tabs['routes']);
        }
        
        if ($this->exists) {
            if ($this->is_headquarter) {
                if (!$this->is_location) {
                    unset($tabs['routes']);
                }
            } else {
                if ($this->is_location) {
                    unset($tabs['categories'], $tabs['products'], $tabs['actions']);
                }
            }
        }
        
        return $tabs;
    }
    
    /**
     * @return int
     */
    public function getPaymentPeriodInDaysAttribute()
    {
        if ($this->exists) {
            return (int) substr($this->payment_period, 0, 2);
        }
        
        return 0;
    }
    
    /**
     * @return array
     */
    public function getDocumentTypes()
    {
        $document_types = get_codebook_opts('document_type')->pluck('name', 'code')->toArray();
        
        if ($this->exists) {
            if ($this->type_id == 'private_client') {
                unset($document_types['preorder'], $document_types['offer']);
            } else if ($this->type_id == 'business_client') {
                unset($document_types['cash']);
            }
        }
        
        return $document_types;
    }
    
    /**
     * @return string
     */
    public function getPublicUrlAttribute()
    {
        if ($this->exists) {
            return route('track.client.show', ['hash' => $this->public_hash, 'id' => $this->id]);
        }
        
        return url('/');
    }
}
