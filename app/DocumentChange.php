<?php

namespace App;

/**
 * Class DocumentChange
 *
 * @package App
 */
class DocumentChange extends BaseModel
{
  
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'document_changes';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_id',
        'changed_by',
        'product_id',
        'type',
        'value',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
		'document_id' => 'integer',
		'changed_by' => 'integer',
		'product_id' => 'integer',
		'type' => 'string',
		'value' => 'string',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'type_desc',
    ];
    
    /**
     * Rows per page / query.
     *
     * @var int
     */
    public $limit = null;

    /**
     * DocumentChange belongs to one document
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rDocument()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
    
    /**
     * DocumentChange belongs to one user
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rChangedBy()
    {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }

    /**
     * DocumentChange belongs to one product
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Return list of ordered products.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sPaginate();
    }
    
    /**
     * @return string
     */
    public function getTypeDescAttribute()
    {
        if ($this->exists) {
            return trans('document.changes.'.$this->type);
        }
        
        return '-';
    }
}
