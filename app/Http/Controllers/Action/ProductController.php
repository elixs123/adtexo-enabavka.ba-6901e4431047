<?php

namespace App\Http\Controllers\Action;

use App\Action;
use App\ActionProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Action\UpdateActionProductRequest;
use App\Product;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers\Action
 */
class ProductController extends Controller
{
    /**
     * @var \App\ActionProduct
     */
    private $actionProduct;
    
    /**
     * @var \App\Action
     */
    private $action;
    /**
     * @var \App\Product
     */
    private $product;
    
    /**
     * ProductController constructor.
     *
     * @param \App\ActionProduct $actionProduct
     * @param \App\Action $action
     * @param \App\Product $product
     */
    public function __construct(ActionProduct $actionProduct, Action $action, Product $product)
    {
        $this->actionProduct = $actionProduct;
        $this->action = $action;
        $this->product = $product;
    
        $this->middleware('auth');
        $this->middleware('acl:edit-action', ['only' => ['edit', 'update']]);
    }
    
    /**
     * @param $id
     * @return array|\Illuminate\Actions\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function edit($id)
    {
        $action = $this->action->getOne($id);
    
        $action_products = $action->rProducts->keyBy('product_id');
        
        $this->product->limit = null;
        $this->product->productIds = ($action_products->count()) ? $action_products->pluck('product_id')->toArray() : ['0'];
        $products = $this->product->relation(['rProductQuantities'], true)->getAll()->keyBy('id');
        
        $products_action = $action_products->where('type', 'action');
        $products_gratis = $action_products->where('type', 'gratis');
        
        return view('action.product.form')
            ->with('method', 'post')
            ->with('form_url', route('action.products', [$id]))
            ->with('form_title', trans('action.title').' - '.$action->name.': '.trans('action.actions.products'))
            ->with('item', $this->actionProduct)
            ->with('action', $action)
            ->with('products', $products)
            ->with('products_action', $products_action)
            ->with('products_gratis', $products_gratis)
            ->with('stock', $action->rStock);
        
    }
    
    /**
     * @param \App\Http\Requests\Action\UpdateActionProductRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateActionProductRequest $request, $id)
    {
        $action = $this->action->getOne($id);
    
        $action_qty = (int) $request->get('action_qty');
        
        $removed = $request->get('r', []);
        
        $attributes['action'] = $this->parseDataForActionProducts($request, 'action', $action->rStock->country_id, $action->type_id);
        $attributes['gratis'] = $this->parseDataForActionProducts($request, 'gratis', $action->rStock->country_id, $action->type_id);
        
        $this->dbTransaction(function() use($action, $action_qty, $attributes, $removed) {
            $this->updateOrCreateActionProduct($action->id, $attributes['action'], 'action');
            
            $this->removeActionProduct($action->id, $removed, 'action');
            
            if ($action->isGratis()) {
                $this->updateOrCreateActionProduct($action->id, $attributes['gratis'], 'gratis');
    
                $this->removeActionProduct($action->id, $removed, 'gratis');
            }
            
            $action->update([
                'qty' => $action_qty,
            ]);
            
            $action->updateTotals();
        });
    
        return $this->getUpdateJsonResponse($action, 'action._row', trans('action.notifications.products'));
    }
    
    /**
     * @param \App\Http\Requests\Action\UpdateActionProductRequest $request
     * @param string $type
     * @param string $countryId
     * @param string $actionType
     * @return array
     */
    private function parseDataForActionProducts($request, $type, $countryId, $actionType)
    {
        $update = $request->get('u', []);
        $create = $request->get('c', []);
        $quantities = $request->get('q', []);
        $discounts = $request->get('d', []);
    
        $update = isset($update[$type]) ? $update[$type] : [];
        $create = isset($create[$type]) ? $create[$type] : [];
        $quantities = isset($quantities[$type]) ? $quantities[$type] : [];
        $discounts = isset($discounts[$type]) ? $discounts[$type] : [];
        
        $attributes = [];
        foreach ($create as $product_id) {
            $product_id = (int) $product_id;
        
            if (isset($quantities[$product_id])) {
                $product = $this->product->getOne($product_id);
                
                $prices = $this->parseProductPrices($product, $countryId);

                $attributes[(int) $product_id] = [
                    'qty' => (int) $quantities[$product_id],
                    'vpc_discount' => ($actionType == 'discount') ? (isset($discounts[$product_id]['vpc']) ? convert2float($discounts[$product_id]['vpc']) : 0) : 0,
                    'mpc_discount' => ($actionType == 'discount') ? (isset($discounts[$product_id]['mpc']) ? convert2float($discounts[$product_id]['mpc'])  : 0) : 0,
                    'prices' => $prices,
                    'type' => $type,
                ];
            }
        }
        
        foreach ($update as $product_id) {
            $product_id = (int) $product_id;
        
            if (isset($quantities[$product_id])) {
                $product = $this->product->getOne($product_id);
    
                $prices = $this->parseProductPrices($product, $countryId);
                
                $attributes[(int) $product_id] = [
                    'qty' => (int) $quantities[$product_id],
                    'vpc_discount' => ($actionType == 'discount') ? (isset($discounts[$product_id]['vpc']) ? convert2float($discounts[$product_id]['vpc'])  : 0) : 0,
                    'mpc_discount' => ($actionType == 'discount') ? (isset($discounts[$product_id]['mpc']) ? convert2float($discounts[$product_id]['mpc'])  : 0) : 0,
                    'prices' => $prices,
                ];
            }
        }
        
        return $attributes;
    }
    
    /**
     * @param \App\Product $product
     * @param string $countryId
     * @return array
     */
    private function parseProductPrices($product, $countryId)
    {
        return $product->rProductPrices()->where('country_id', $countryId)->get()->map(function($price) use ($countryId) {
            $price = $price->toArray();
            $price['badge_id'] = null;
            unset($price['created_at'], $price['updated_at'], $price['uid']);
            
            // $suffix = ($countryId == 'bih') ? '' : '_eur';
    
            return [
                'mpc' => $price['mpc'],
                'mpc_old' => $price['mpc_old'],
                'vpc' => $price['vpc'],
                'vpc_old' => $price['vpc_old'],
            ];
        })->first();
    }
    
    /**
     * @param int $actionId
     * @param array $attributes
     * @param string $type
     */
    private function updateOrCreateActionProduct($actionId, $attributes, $type)
    {
        foreach ($attributes as $product_id => $data) {
            ActionProduct::updateOrCreate([
                'action_id' => $actionId,
                'product_id' => $product_id,
                'type' => $type,
            ], $data);
        }
    }
    
    /**
     * @param int $actionId
     * @param array $removed
     * @param string $type
     */
    private function removeActionProduct($actionId, $removed, $type)
    {
        if (isset($removed[$type]) && !empty($removed[$type])) {
            ActionProduct::where('action_id', $actionId)->whereIn('product_id', $removed[$type])->where('type', $type)->delete();
        }
    }
    
    /**
     * @param int $productId
     * @param string $countryId
     * @return array
     */
    private function parseDataForGratisProduct($productId, $countryId)
    {
        if (!is_null($productId)) {
            $product = $this->product->getOne($productId);
            
            if (!is_null($product)) {
                return [
                    'product_id' => $productId,
                    'product_prices' => $this->parseProductPrices($product, $countryId),
                ];
            }
        }
        
        return [
            'product_id' => null,
            'product_prices' => [],
        ];
    }
}
