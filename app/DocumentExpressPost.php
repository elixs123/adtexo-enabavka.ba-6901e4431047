<?php

namespace App;

use App\Support\Model\DocumentHelper;
use App\Support\Model\Search;
use App\Support\Model\Stock;

/**
 * Class DocumentExpressPost
 *
 * @package App
 */
class DocumentExpressPost extends BaseModel
{
    use Search, DocumentHelper, Stock;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'document_express_posts';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stock_id',
        'document_id',
        'express_post_type',
        'shipment_id',
        'tracking_number',
        'pdf_label_path',
        'pdf_pickup_path',
        'traces',
        'status',
        'picked_at',
        'delivered_at',
        'viewed_at',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'stock_id' => 'integer',
        'document_id' => 'integer',
        'traces' => 'array',
        'picked_at' => 'datetime',
        'delivered_at' => 'datetime',
        'viewed_at' => 'datetime',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'uid',
        'express_post_name',
    ];
    
    /**
     * @var null|string|array
     */
    public $expressPostType = null;
    
    /**
     * @var null|string|array
     */
    public $statusId = null;
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rExpressPostEvents()
    {
        return $this->hasMany(ExpressPostEvent::class, 'document_id', 'document_id');
    }
    
    /**
     * Return list of items.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sStock()
            ->sDocument()
            ->sExpressPostType()
            ->sStatus()
            ->sSearch()
            ->orderBy('created_at', 'desc')
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
            return $query->where('shipment_id', 'like', '%'.$this->keywords.'%')
                ->orWhere('tracking_number', 'like', '%'.$this->keywords.'%');
        }
    }
    
    /**
     * Scope: Status.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesStatus($query)
    {
        if (is_array($this->statusId) && isset($this->statusId[0])) {
            $query->whereIn('status', $this->statusId);
        } else if (!is_null($this->statusId) && $this->statusId) {
            $query->where('status', $this->statusId);
        }
        
        return $query;
    }
    
    /**
     * Scope: Express post type.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesExpressPostType($query)
    {
        if (is_array($this->expressPostType) && isset($this->expressPostType[0])) {
            $query->whereIn('express_post_type', $this->expressPostType);
        } else if (!is_null($this->expressPostType) && $this->expressPostType) {
            $query->where('express_post_type', $this->expressPostType);
        }
        
        return $query;
    }
    
    /**
     * @return mixed|string
     */
    public function getExpressPostNameAttribute()
    {
        if ($this->exists) {
            $posts = config('express_post.types');
            
            if (isset($posts[$this->express_post_type])) {
                return $posts[$this->express_post_type];
            }
        }
        
        return '-';
    }
    
    /**
     * @return null|string
     */
    public function getTrackingNumberValueAttribute()
    {
        if ($this->exists) {
            if (is_null($this->tracking_number)) {
                $event = $this->rExpressPostEvents()->oldest()->first();
                
                if (!is_null($event)) {
                    return $event->response->Collies[0]->BarCode;
                }
            } else {
                return $this->tracking_number;
            }
        }
        
        return null;
    }
}
