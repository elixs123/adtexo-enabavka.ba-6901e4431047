<?php

namespace App;

/**
 * Class PaymentItem
 *
 * @package App
 */
class PaymentItem extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_items';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_id',
        'document_id',
        'amount',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'payment_id' => 'integer',
        'document_id' => 'integer',
        'amount' => 'float',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rPayment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
}
