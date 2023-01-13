<?php

namespace App;
use App\Support\Model\Search;
use App\Support\Model\DateFromToHelper;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Log
 *
 * @package App
 */
class Log extends BaseModel
{
    use Search, DateFromToHelper, SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logs';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loggable_id',
        'loggable_type',
        'body'
    ];
    
	
    /**
     * Get the parent loggable model (document or client).
     */
    public function loggable()
    {
        return $this->morphTo();
    }

    /**
     * Return list of documents.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sDateFromTo()
            ->sSearch()
            ->orderBy('id', 'desc')
            ->sPaginate();
    }

    /**
     * Search by.
     *
     * @return string
     */
    protected function searchBy()
    {
        return 'loggable_id';
    }
}
