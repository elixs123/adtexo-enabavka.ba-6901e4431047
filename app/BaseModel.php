<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 *
 * @package App
 */
abstract class BaseModel extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * If is true, eloquent paginate method will be called.
     *
     * @var boolean
     */
    public $paginate = false;
    
    /**
     * Rows per page / query.
     *
     * @var int
     */
    public $limit = 10;
    
    /**
     * Offset.
     *
     * @var int
     */
    public $offset = 0;
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'uid',
    ];
    
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [];
    
    /**
     * The relationship counts that should be eager loaded on every query.
     *
     * @var array
     */
    protected $withCount = [];
    
    /**
     * List of fields used for listing.
     *
     * @var array
     */
    public $listFields = [
        '*'
    ];

    /**
     * List of fields used for item display.
     *
     * @var array
     */
    public $itemFields = [
        '*'
    ];
    
    /**
     * Store new item.
     *
     * @param array $data
     * @return self
     */
    public function add($data)
    {
        return self::create($data);
    }
    
    /**
     * Update item
     * @param int $id
     * @param array $data
     * @return self
     */
    public function edit($id, $data)
    {
        $item = self::find($id);
    
        $item->update($data);
        
        return $item;
    }
    
    /**
     * Update or create item.
     *
     * @param int $id
     * @param array $data
     * @return self
     */
    public function editOrAdd($id, $data)
    {
        return self::updateOrCreate(['id' => $id], $data);
    }
    
    /**
     * Remove item from table.
     *
     * @param int $id
     * @return boolean
     */
    public function remove($id)
    {
        return self::destroy($id);
    }
    
    /**
     * Get data about item
     * @param int $id
     * @return self
     */
    public function getOne($id)
    {
        return self::find($id);
    }
    
    /**
     * Return list of items
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        $query = $this->sOrder();
        
        return $this->limit == 1 ? $query->first($this->listFields) : $query->sPaginate();
    }
    
    /**
     * Scope: Order.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesOrder($query)
    {
        return $query->orderBy('id', 'desc');
    }
    
    /**
     * Scope: Offset.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesOffset($query)
    {
        if($this->offset > 0) {
            $query->skip($this->offset);
        }
        
        return $query;
    }

    /**
     * Pagination and limiting.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function scopesPaginate($query)
    {
        if ($this->paginate == true) {
            return $query->sOffset()->paginate($this->limit, $this->listFields);
        }
        elseif ($this->limit != null) {
            return $query->sOffset()->take($this->limit)->get($this->listFields);
        } else {
            return $query->get($this->listFields);
        }
    }
    
    /**
     * Convert model attribute value to NULL if empty.
     *
     * @param mixed $field
     * @return mixed
    */
    public function nullIfBlank($field)
    {
        return trim($field) !== '' ? $field : null;
    }
    
    /**
     * Relation with.
     *
     * @param array $with
     * @param bool $onlyThis
     * @return self
     */
    public function relation(array $with = [], $onlyThis = false)
    {
        $this->with = $onlyThis ? $with : array_merge($this->with, $with);
        
        // self::with($onlyThis ? $with : $this->relationWith + $with);
        // dump(class_basename($this).': '.implode(', ', $this->with));
        
        return $this;
    }
    
    /**
     * Relation with count.
     *
     * @param array $withCount
     * @param bool $onlyThis
     * @return self
     */
    public function relationCount(array $withCount = [], $onlyThis = false)
    {
        $this->withCount =  $onlyThis ? $withCount : array_merge($this->withCount, $withCount);
        
        return $this;
    }
    
    /**
     * Uid attribute.
     *
     * @return string
     */
    public function getUidAttribute()
    {
        return $this->getTable().$this->id;
    }
}
