<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductStockRequest;
use App\Product;
use App\ProductQuantity;
use App\Stock;
use App\ProductStock;

/**
 * Class ProductStockController
 *
 * @package App\Http\Controllers
 */
class ProductStockController extends Controller
{
    /**
     * @var \App\Product
     */
    private $product;

    /**
     * @var \App\ProductStock
     */
    private $productStock;

    /**
     * @var \App\Stock
     */
    private $stock;

    /**
     * ProductController constructor.
     *
     * @param \App\Product $product
     * @param \App\ProductStock $productStock
     * @param \App\Stock $stock
     */
    public function __construct(
        Product $product,
        ProductStock $productStock,
        Stock $stock
    ) {
        $this->product = $product;
        $this->productStock = $productStock;
        $this->stock = $stock;

        $this->middleware('auth');
        $this->middleware('emptystringstonull');
        $this->middleware('acl:edit-product', ['only' => ['edit', 'update', 'create', 'store', 'destroy']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $productId = request('product_id');

        $stocks = $this->stock->getAll();

        return view('product.stock.form')
            ->with('item', $this->productStock)
            ->with('stocks', $stocks)
            ->with('product_id', $productId)
            ->with('method', 'post')
            ->with('form_url', route('product-stock.store'))
            ->with('form_title', trans('product.actions.create_supplies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Product\StoreProductStockRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreProductStockRequest $request)
    {
        $item = request()->except(['_token']);

        // Save Product
        $productStock = $this->productStock->add($item);
        
        $total_stock = ProductQuantity::updateAvailableQty($productStock->stock_id, $productStock->product_id);

        return $this->getStoreJsonResponse($productStock, 'product.stock._row', null,  [
            'stock' => $total_stock
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $productId = request('product_id');

        $item = $this->productStock->getOne($id);

        $stocks = $this->stock->getAll();

        return view('product.stock.form')
            ->with('method', 'put')
            ->with('form_url', route('product-stock.update', [$id]))
            ->with('form_title', trans('product.actions.edit_supplies'))
            ->with('stocks', $stocks)
            ->with('product_id', $productId)
            ->with('item', $item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Product\StoreProductStockRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(StoreProductStockRequest $request, $id)
    {
        $item = request()->except(['_token', 'product_id']);

        $productStock = $this->productStock->edit($id, $item);
    
        $total_stock = ProductQuantity::updateAvailableQty($productStock->stock_id, $productStock->product_id);
  
        return $this->getUpdateJsonResponse($productStock, 'product.stock._row', null, [
            'stock' => $total_stock
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy($id)
    {
        $item = $this->productStock->getOne($id);

        $this->productStock->remove($id);
    
        $total_stock = ProductQuantity::updateAvailableQty($item->stock_id, $item->product_id);

        return $this->getDestroyJsonResponse($item, null, null, [
            'stock' => $total_stock
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function massCreate()
    {
        $stocks = $this->stock->getAll();

        return view('product.stock.form_mass')
            ->with('item', $this->productStock)
            ->with('stocks', $stocks)
            ->with('method', 'post')
            ->with('form_url', route('product_stock.mass_store'))
            ->with('form_title', trans('product.actions.create_supplies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Product\StoreProductStockRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function massStore(StoreProductStockRequest $request)
    {
        $item = request()->except(['_token']);

        // Save Product
        $productStock = $this->productStock->add($item);
    
        ProductQuantity::updateAvailableQty($productStock->stock_id, $productStock->product_id);

        return $this->getStoreJsonResponse($productStock, null, null, ['close_modal' => false]);

    }
}
