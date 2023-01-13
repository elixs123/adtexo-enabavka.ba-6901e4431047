<?php

namespace App;

class ProductStock extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = []; 

    public function rStock()
    {
        return $this->belongsTo('App\Stock', 'stock_id', 'id');
    }   
    
    public function rProduct()
    {
        return $this->belongsTo('App\Product');
    }      

    public function getProductQtyPerStock($productId)
    {
        return self::join('stocks', 'stocks.id', '=', 'product_stocks.stock_id')
                    ->where('product_stocks.product_id', $productId)
                    ->select('stocks.name as stock', \DB::raw('SUM(qty) as qty'))
					->groupBy('stocks.id')
                    ->get();
    }
   
}