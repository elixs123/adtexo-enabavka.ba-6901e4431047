<?php

namespace App;

use App\Support\Model\DocumentHelper;
use App\Support\Model\Search;

/**
 * Class ExpressPostEvent
 *
 * @package App
 */
class ExpressPostEvent extends BaseModel
{
    use DocumentHelper;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'express_post_events';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_id',
        'type',
        'response',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'document_id' => 'integer',
        'response' => 'object',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'uid',
    ];
    
    /**
     * Return list of items.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sDocument()
            ->orderBy('created_at', 'desc')
            ->sPaginate();
    }
}
