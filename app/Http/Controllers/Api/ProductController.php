<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\ProductPrice;
use App\ProductTranslation;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource as ModelResource;
use App\Http\Requests\Product\StoreApiProductRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers\Api
 */
class ProductController extends Controller
{
    /**
     * @var \App\Product
     */
    private $product;

    /**
     * @var \App\ProductTranslation
     */
    private $productTranslation;
    
    /**
     * DocumentController constructor.
     *
     * @param \App\Product $product
     * @param \App\ProductTranslation $productTranslation
     */
    public function __construct(Product $product, ProductTranslation $productTranslation) {
        $this->product = $product;
        $this->productTranslation = $productTranslation;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreApiProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreApiProductRequest $request, ProductPrice $productPrice)
    {
        $item = $request->get('item');
        $translation = $request->get('translation');
        
        $prices = $request->get('prices', []);
        
        $promo = $request->get('promo', []);
    
        $product = $this->dbTransaction(function () use ($item, $translation, $prices, $promo, $productPrice) {
            // Save or update Product
            $product = $this->product->updateOrCreate(['code' => $item['code']], $item);
    
            // Save Translation
            $translation['product_id'] = $product->id;
            $translation = $this->productTranslation->updateOrCreate(['product_id' => $product->id, 'lang_id' => $translation['lang_id']], $translation);
            $product->translation = $translation;
    
            // Save  or sync prices
            $productPrice->syncItems($product->id, $prices, false);
    
            // Update link and search
            $this->productTranslation->updateLink($product->id, $translation['lang_id']);
            
            // Promo articles
            if (intval($product->category_id) == Product::PROMO_CATEGORY_ID) {
                $product->rPromoItems()->detach();
    
                $attributes = array_map(function ($value) use ($product) {
                    return [
                        'product_id' => $product->id,
                        'product_code' => $value['code'],
                        'promo_qty' => $value['qty'],
                    ];
                }, $promo);
                
                DB::table('promo_products')->insert($attributes);
                
                // $attributes = collect($promo)->groupBy('code')->map(function ($items) {
                //     return [
                //         'promo_qty' => $items->sum('qty'),
                //     ];
                // })->toArray();
                //
                // $product->rPromoItems()->sync($attributes);
            }
            
            // Return
            return $product;
        });


        return new ModelResource($product);
    }

    /**
     * @param int $id
     * @return \App\Http\Resources\Client\ClientResource
     */
    public function show($id)
    {
        $item = $this->product->getOne($id);

        return new ModelResource($item);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function syncPrices()
    {
        $data = request()->all();
        $failed =  [];
        $updated = 0;

        foreach ($data as $item) {
            try {
                $pc = new ProductPrice();
                $pc->removeItems($item['product_id']);
                $pc->insert($item);
                $updated++;

            } catch (\Exception $exception) {
                $failed[] = $item['product_id'];
            }
        }

        return [
            'status' => 'success',
            'failed' => $failed,
            'updated_total' => $updated
        ];
    }
}
