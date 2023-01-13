<?php

namespace App;

use App\Support\Model\Currency;
use App\Support\Model\PaymentType;
use App\Support\Model\Status;

/**
 * Class Payment
 *
 * @package App
 */
class Payment extends BaseModel
{
    use Status;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'service',
        'file',
        'uploaded_at',
        'uploaded_by',
        'confirmed_at',
        'confirmed_by',
        'config',
        'total_payments',
        'total_documents',
        'status',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'uploaded_at' => 'datetime',
        'uploaded_by' => 'integer',
        'confirmed_at' => 'datetime',
        'confirmed_by' => 'integer',
        'config' => 'array',
        'total_payments' => 'float',
        'total_documents' => 'float',
    ];
    
    /**
     * @var null|string|array
     */
    public $typeVal = null;
    
    /**
     * @var null|string|array
     */
    public $serviceVal = null;
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rUploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rConfirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rPaymentItems()
    {
        return $this->hasMany(PaymentItem::class, 'payment_id', 'id');
    }
    
    /**
     * Return list of clients.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sStatus()
            ->sType()
            ->sService()
            ->sOrder()
            ->sPaginate();
    }
    
    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesType($query)
    {
        if (is_array($this->typeVal) && isset($this->typeVal[0])) {
            $query->whereIn('type', $this->typeVal);
        } else if (!is_null($this->typeVal) && $this->typeVal) {
            $query->where('type', $this->typeVal);
        }
        
        return $query;
    }
    
    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesService($query)
    {
        if (is_array($this->serviceVal) && isset($this->serviceVal[0])) {
            $query->whereIn('service', $this->serviceVal);
        } else if (!is_null($this->serviceVal) && $this->serviceVal) {
            $query->where('service', $this->serviceVal);
        }
        
        return $query;
    }
}
