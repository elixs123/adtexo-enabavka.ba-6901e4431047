<?php

namespace App;

use App\Support\Model\DocumentHelper;

/**
 * Class DocumentTakeover
 *
 * @package App
 */
class DocumentTakeover extends BaseModel
{
    use DocumentHelper;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'document_takeovers';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_id',
        'name',
        'picked_at',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'document_id' => 'integer',
        'picked_at' => 'datetime',
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
