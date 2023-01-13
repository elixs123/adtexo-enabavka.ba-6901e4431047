<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductQuantity;
use Illuminate\Support\Facades\DB;

/**
 * Class QuantityController
 *
 * @package App\Http\Controllers\Product
 */
class QuantityController extends Controller
{
    /**
     * @var \App\ProductQuantity
     */
    private $productQuantity;
    /**
     * @var \App\Product
     */
    private $product;
    
    /**
     * QuantityController constructor.
     *
     * @param \App\ProductQuantity $productQuantity
     * @param \App\Product $product
     */
    public function __construct(ProductQuantity $productQuantity, Product $product)
    {
        $this->productQuantity = $productQuantity;
        $this->product = $product;
        
        $this->middleware('auth');
    }
    
    public function parse()
    {
        $stocks = DB::table('stocks')->get()->pluck('id')->toArray();
        
        $products = DB::table('product_stocks')->groupBy('product_id')->orderBy('product_id')->get()->pluck('product_id')->toArray();
        
        $data = [];
        foreach ($stocks as $stock_id) {
            foreach ($products as $product_id) {
                $count = DB::table('product_stocks')->where('stock_id', $stock_id)->where('product_id', $product_id)->count();
                
                if ($count > 0) {
                    $qty = DB::table('product_stocks')->where('stock_id', $stock_id)->where('product_id', $product_id)->sum('qty');
    
                    $data[] = [
                        'stock_id' => $stock_id,
                        'product_id' => $product_id,
                        'available_qty' => ($qty < 0) ? 0 : $qty,
                        'created_at' => now()->toDateTimeString(),
                        'updated_at' => now()->toDateTimeString(),
                    ];
                }
            }
        }
    
        if (isset($data[0])) {
            $chunked = array_chunk($data, 1000, true);
            foreach ($chunked as $chunk) {
                DB::table('product_quantities')->insert($chunk);
            }
        }
    }
}
