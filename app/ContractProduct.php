<?php

namespace App;

use \App\Support\Model\Contract;

/**
 * Class ContractProduct
 *
 * @package App
 */
class ContractProduct extends BaseModel
{
    use Contract;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contract_products';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contract_id',
        'product_id',
        'discount',
        'qty',
        'bought',
        'prices',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'contract_id' => 'integer',
        'product_id' => 'integer',
        'discount' => 'decimal:2',
        'qty' => 'integer',
        'bought' => 'integer',
        'prices' => 'object',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'uid',
        'in_stock',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    
    /**
     * Return list of documents.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sContract()
            ->orderBy('id', 'desc')
            ->sPaginate();
    }
    
    /**
     * @param int $id
     * @return self
     */
    public function getOne($id)
    {
        return $this->sContract()
            ->where($this->table.'.id', $id)
            ->first();
    }
    
    /**
     * @return int|mixed
     */
    public function getInStockAttribute()
    {
        if ($this->exists) {
            return $this->qty - $this->bought;
        }
        
        return 0;
    }
}
