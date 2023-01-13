<?php

namespace App;

use App\Support\Model\Country;
use App\Support\Model\IncludeExclude;
use App\Support\Model\Search;
use App\Support\Model\Status;
use App\Support\Model\Type;
use App\Support\Model\User;
use App\Support\Model\Stock;

/**
 * Class Person
 *
 * @package App
 */
class Person extends BaseModel
{
    use IncludeExclude, Search, Status, Type, User, Stock, Country;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'persons';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'type_id',
        'email',
        'phone',
        'note',
        'code',
        'stock_id',
        'status',
        'printer_type',
        'printer_receipt_url',
        'printer_access_token',
        'kpi_values',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'int',
        'name' => 'string',
        'type_id' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'note' => 'string',
        'code' => 'string',
        'stock_id' => 'integer',
        'status' => 'string',
        'printer_type' => 'string',
        'printer_receipt_url' => 'string',
        'printer_access_token' => 'string',
        'kpi_values' => 'array',
    ];
    
    /**
     * @var null|string
     */
    public $roleName = null;
    
    /**
     * @var bool
     */
    public $withUser = false;
    
    /**
     * Relation: Routes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rRoutes()
    {
        return $this->hasMany(Route::class, 'person_id', 'id');
    }
    
    /**
     * Relation: Categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rCategories()
    {
        return $this->belongsToMany(Category::class , 'person_category');
    }
    
    /**
     * Relation: Products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rProducts()
    {
        return $this->belongsToMany(Product::class, 'person_product');
    }
    
    /**
     * Relation: Clients.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rClients()
    {
        return $this->belongsToMany(Client::class, 'routes', 'person_id', 'client_id')->withPivot(['week', 'day', 'rank'])->withTimestamps();
    }
    
    /**
     * Return list of persons.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sType()
            ->sSearch()
            ->sStatus()
            ->sIncludeIds()
            ->sRole()
            ->sUser()
            ->sStockCountry()
            ->sWithUser()
            ->orderBy('name', 'asc')
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
            return $query->where('name', 'like', '%'.$this->keywords.'%')->orWhere('email', 'like', '%'.$this->keywords.'%');
        }
    }
    
    /**
     * Scope a query to filter users per role
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param string|null $role
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesRole($query)
    {
        if (is_null($this->roleName)) {
            return $query;
        }
        
        return $query->whereHas('rUser.roles', function($query) {
            $query->where('name', $this->roleName);
        });
    }
    
    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesWithUser($query)
    {
        if ($this->withUser === true) {
            return $query->whereNotNull('user_id');
        }

        return $query;
    }
    
    /**
     * Person types.
     *
     * @param null $id
     * @return array|mixed
     */
    public function getTypes($id = null)
    {
        $types = $this->getCodeBookOptions('person_types', $id);
    
        if (userIsSalesman()) {
            unset($types['salesman_person']);
        }
        
        return $types;
    }
}
