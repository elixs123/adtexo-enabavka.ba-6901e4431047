<?php

namespace App;

use App\Support\Model\Search;

/**
 * Class CodeBook
 *
 * @package App
 */
class CodeBook extends BaseModel
{
	use RecordActivity, Search;
  	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @var null|string
     */
    public $typeGroup = null;
	
    /**
     * Rows per page / query.
     *
     * @var int
     */
    public $limit = 50;

    /**
     * Filter by type
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesType($query)
    {
        if ($this->typeGroup != null) {
            return $query->where('type', '=', $this->typeGroup);
        }
    }

    /**
     * Return list of code_books
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sType()
            ->sSearch()
            ->orderBy('type', 'asc')
            ->orderBy('name', 'asc')
            ->sPaginate();
    }
	
    /**
     * Code name attribute.
     *
     * @return string
     */
    public function getCodeNameAttribute()
    {
        return $this->code.' - '.$this->name;
    }

}
