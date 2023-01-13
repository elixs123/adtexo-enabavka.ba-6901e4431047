<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stock\StoreStockRequest;
use App\Http\Requests\Stock\UpdateStockRequest;
use App\Stock;

/**
 * Class StockController
 *
 * @package App\Http\Controllers
 */
class StockController extends Controller
{
    /**
     * @var \App\Stock
     */
    private $stock;
    
    /**
     * StockController constructor.
     *
     * @param \App\Stock $Stock
     */
    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
		
        $this->middleware('auth');
        $this->middleware('acl:view-stock', ['only' => ['index']]);
        $this->middleware('acl:create-stock', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-stock', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->stock->paginate = true;
        $this->stock->statusId = request('status');
        $this->stock->countryId = request('country_id');
        $this->stock->keywords = request('keywords');
        $items = $this->stock->relation(['rStatus', 'rCountry'])->getAll();
        
        return view('stock.index')->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('stock.form')
                ->with('item', $this->stock)
                ->with('method', 'post')
                ->with('form_url', route('stock.store'))
                ->with('form_title', trans('stock.actions.create'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Stock\StoreStockRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreStockRequest $request)
    {
        // Get form data
        $input = $request->except(['_token', '_method']);
		
        // Save Stock
        $stock = $this->stock->create($input);
     
        return $this->getStoreJsonResponse($stock, 'stock._row', trans('stock.notifications.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->stock->getOne($id);
		
        return view('stock.form')
                ->with('method', 'put')
                ->with('form_url', route('stock.update', [$id]))
                ->with('form_title', trans('stock.actions.edit'))
                ->with('item', $item);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Stock\UpdateStockRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateStockRequest $request, $id)
    {
        $input = $request->except(['_token', '_method']);
        
        // Change Stock data
        $stock = $this->stock->edit($id, $input);
        
        return $this->getUpdateJsonResponse($stock, 'stock._row', trans('stock.notifications.updated'));
    }
}
