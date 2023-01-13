<?php

namespace App;

use Illuminate\Support\Facades\DB;

/**
 * Class ProductQuantity
 *
 * @package App
 */
class ProductQuantity extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_quantities';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'stock_id',
        'available_qty',
        'reserved_qty',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_id' => 'integer',
        'stock_id' => 'integer',
        'available_qty' => 'float',
        'reserved_qty' => 'float',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'uid',
        'qty',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rStock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    
    /**
     * @param int $stockId
     * @param int $productId
     * @return mixed
     */
    public static function updateAvailableQty($stockId, $productId)
    {
        $qty = DB::table('product_stocks')->where('stock_id', $stockId)->where('product_id', $productId)->sum('qty');
        
        self::updateOrCreate([
            'stock_id' => $stockId,
            'product_id' => $productId,
        ], [
            'available_qty' => ($qty < 0) ? 0 : $qty,
        ]);
        
        return $qty;
    }
    
    /**
     * @param int $stockId
     * @param int $productId
     * @param int $qty
     * @return mixed
     */
    public static function incrementReservedQty($stockId, $productId, $qty)
    {
        return DB::table('product_quantities')->where('stock_id', $stockId)->where('product_id', $productId)->increment('reserved_qty', $qty);
    }
    
    /**
     * @param int $stockId
     * @param int $productId
     * @param int $qty
     * @return mixed
     */
    public static function decrementReservedQty($stockId, $productId, $qty)
    {
        return DB::table('product_quantities')->where('stock_id', $stockId)->where('product_id', $productId)->decrement('reserved_qty', $qty);
    }
    
    /**
     * @param int $stockId
     * @param int $productId
     * @param int $qty
     */
    public static function resolveReservedQty($stockId, $productId, $qty)
    {
        $product_quantity = DB::table('product_quantities')->where('stock_id', $stockId)->where('product_id', $productId)->first();
        
        if (!is_null($product_quantity)) {
            $reserved_qty = $product_quantity->reserved_qty - $qty;
    
            DB::table('product_quantities')->where('stock_id', $stockId)->where('product_id', $productId)->update([
                'reserved_qty' => ($reserved_qty < 0) ? 0 : $reserved_qty,
            ]);
        }
    }
    
    /**
     * @return int
     */
    public function getQtyAttribute()
    {
        if ($this->exists) {
            return $this->available_qty - $this->reserved_qty;
        }
        
        return 0;
    }
}
