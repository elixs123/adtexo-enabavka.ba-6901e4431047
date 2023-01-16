<?php

namespace App\Http\Controllers\Api;

use App\ProductQuantity;
use App\Stock;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\StockResource as ModelResource;
use App\Http\Resources\Stock\StockCollection as ModelCollection;
use App\Http\Requests\Stock\StoreApiStockRequest;

/**
 * Class StockController
 *
 * @package App\Http\Controllers\Api
 */
class StockController extends Controller
{
    /**
     * @var \App\Stock
     */
    private $stock;

    /**
     * @var \App\Stock
     */
    private $productStock;

    /**
     * StockController constructor.
     *
     * @param \App\Stock $stock
     * @param ProductStock $productStock
     */
    public function __construct(Stock $stock, \App\ProductStock $productStock) {
        $this->stock = $stock;
        $this->productStock = $productStock;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Throwable
     */
    public function index()
    {
        $this->stock->paginate = true;
        $this->stock->limit = request('limit', 100);;
        $this->stock->statusId = request('status');
        $this->stock->countryId = request('country_id');
        $this->stock->keywords = request('keywords');
        $items = $this->stock->relation(['rStatus', 'rCountry'])->getAll();

        return new ModelCollection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreApiStockRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreApiStockRequest $request)
    {
        $input = $request->all();

        $stock = $this->stock->updateOrCreate(['code' => $input['code']], $input);

        return new ModelResource($stock);
    }

    /**
     * @param int $id
     * @return \App\Http\Resources\Client\ClientResource
     */
    public function show($id)
    {
        $item = $this->stock->getOne($id);

        return new ModelResource($item);
    }

    /**
     * @return array
     */
    public function syncQty() {

        $data = request()->all();
        $failed =  [];
        $updated = 0;

        foreach ($data as $item) {
            try {
                ProductQuantity::updateOrCreate([
                    'stock_id' => $item['stock_id'],
                    'product_id' => $item['product_id'],
                ], $item);

                $updated++;

            } catch (\Exception $exception) {
                $failed[] = $item;
            }
        }

        return [
            'status' => 'success',
            'failed' => $failed,
            'updated_total' => $updated
        ];
    }

    /**
     * @return array
     */
    public function productStock() {

        $validated = request()->validate([
            'stock_id' => 'required|integer',
            'product_id' => 'required|integer',
            'qty' => 'required|integer'
        ]);

        $input = request()->all();


        try {
            $productStock = $this->productStock->add($input);

            $total_stock = ProductQuantity::updateAvailableQty($productStock->stock_id, $productStock->product_id);

        } catch (\Exception $exception) {
            return response(['status' => 'failed', 'msg' => $exception->getMessage()], 422);
        }

        return ['status' => 'success', 'stock' => $total_stock];
    }
}