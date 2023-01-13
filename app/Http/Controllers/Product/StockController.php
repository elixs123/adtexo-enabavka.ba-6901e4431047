<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Product;

/**
 * Class StockController
 *
 * @package App\Http\Controllers\Product
 */
class StockController extends Controller
{
    /**
     * @var \App\Product
     */
    private $product;
    
    /**
     * StockController constructor.
     *
     * @param \App\Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    
        $this->middleware('auth');
        $this->middleware('acl:view-product', ['only' => ['index']]);
    }
    
    /**
     * @param int $productId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index($productId)
    {
        $product = $this->product->getOne($productId);
        abort_if(is_null($product), 404);
        
        $items = $product->rProductStocks()->with('rStock')->latest()->paginate();
        
        return view('product.stock.index')->with([
            'product' => $product,
            'items' => $items,
        ]);
    }
}
