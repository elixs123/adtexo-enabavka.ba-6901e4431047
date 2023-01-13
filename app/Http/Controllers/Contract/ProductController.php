<?php

namespace App\Http\Controllers\Contract;

use App\Contract;
use App\ContractProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contract\UpdateContractProductRequest;
use App\Product;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers\Contract
 */
class ProductController extends Controller
{
    /**
     * @var \App\ContractProduct
     */
    private $contractProduct;
    
    /**
     * @var \App\Contract
     */
    private $contract;
    /**
     * @var \App\Product
     */
    private $product;
    
    /**
     * ProductController constructor.
     *
     * @param \App\ContractProduct $contractProduct
     * @param \App\Contract $contract
     * @param \App\Product $product
     */
    public function __construct(ContractProduct $contractProduct, Contract $contract, Product $product)
    {
        $this->contractProduct = $contractProduct;
        $this->contract = $contract;
        $this->product = $product;
    
        $this->middleware('auth');
        $this->middleware('acl:edit-contract', ['only' => ['edit', 'update']]);
    }
    
    /**
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function edit($id)
    {
        $contract = $this->contract->getOne($id);
    
        $contract_products = $contract->rContractProducts;
        
        $this->product->limit = null;
        $this->product->productIds = ($contract_products->count()) ? $contract_products->pluck('product_id')->toArray() : ['0'];
        $products = $this->product->getAll()->keyBy('id');
    
        return view('contract.product.form')
            ->with('method', 'post')
            ->with('form_url', route('contract.products', [$id]))
            ->with('form_title', trans('contract.title').' - '.$contract->rClient->name.': '.trans('contract.actions.products'))
            ->with('item', $this->contractProduct)
            ->with('contract_products', $contract_products)
            ->with('products', $products)
            ->with('contract', $contract);
        
    }
    
    /**
     * @param \App\Http\Requests\Contract\UpdateContractProductRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateContractProductRequest $request, $id)
    {
        $contract = $this->contract->getOne($id);
        
        $removed = $request->get('r', []);
        
        $attributes = $this->parseDataForContractProducts($request, $contract->rClient->country_id);
        
        $this->dbTransaction(function() use($contract, $attributes, $removed) {
            foreach ($attributes as $product_id => $data) {
                ContractProduct::updateOrCreate([
                    'contract_id' => $contract->id,
                    'product_id' => $product_id,
                ], $data);
            }
    
            if (!empty($removed)) {
                ContractProduct::where('contract_id', $contract->id)->whereIn('product_id', $removed)->delete();
            }
            
            $contract->updateTotals();
        });
    
        return $this->getUpdateJsonResponse($contract, 'contract._row', trans('contract.notifications.products'));
    }
    
    /**
     * @param \App\Http\Requests\Contract\UpdateContractProductRequest $request
     * @param string $countryId
     * @return array
     */
    private function parseDataForContractProducts($request, $countryId)
    {
        $update = $request->get('u', []);
        $create = $request->get('c', []);
        $quantities = $request->get('q', []);
        $discounts = $request->get('d', []);
        
        $attributes = [];
        foreach ($create as $product_id) {
            $product_id = (int) $product_id;
        
            if (isset($quantities[$product_id]) && isset($discounts[$product_id])) {
                $product = $this->product->getOne($product_id);
                
                $prices = $product->rProductPrices()->where('country_id', $countryId)->get()->map(function($price) {
                    $price = $price->toArray();
                    $price['badge_id'] = null;
                    unset($price['created_at'], $price['updated_at'], $price['uid']);
                    
                    return $price;
                })->first();

                $attributes[(int) $product_id] = [
                    'discount' => (float) $discounts[$product_id],
                    'qty' => (int) $quantities[$product_id],
                    'prices' => $prices,
                ];
            }
        }
        
        foreach ($update as $product_id) {
            $product_id = (int) $product_id;
        
            if (isset($quantities[$product_id]) && isset($discounts[$product_id])) {
                $attributes[(int) $product_id] = [
                    'discount' => (float) $discounts[$product_id],
                    'qty' => (int) $quantities[$product_id],
                ];
            }
        }
        
        return $attributes;
    }
}
