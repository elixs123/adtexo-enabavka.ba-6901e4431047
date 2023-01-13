<?php

namespace App\Http\Controllers\Action;

use App\DocumentProduct;
use App\Http\Controllers\Controller;
use App\Support\Controller\ActionHelper;
use App\Support\Scoped\ScopedContractFacade as ScopedContract;
use App\Support\Scoped\ScopedDocumentFacade as ScopedDocument;
use Illuminate\Http\Request;

/**
 * Class CartController
 *
 * @package App\Http\Controllers\Action
 */
class CartController extends Controller
{
    use ActionHelper;
    
    /**
     * @var array
     */
    private $addErrors = [];
    
    /**
     * CartController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('acl:create-document', ['only' => ['quantity', 'add']]);
    }
    
    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function quantity($id)
    {
        if (!ScopedDocument::exist()) {
            abort(404);
        }
        
        $action = scopedAction()->getQuery()->findOrFail($id);
        
        $range = [];
        for($i = 1; $i <= $action->available_qty; $i++) {
            $range[$i] = $i;
        }
    
        return view('action.cart.quantity')
            ->with('item', $action)
            ->with('method', 'post')
            ->with('form_url', route('action.cart', ['id' => $id]))
            ->with('form_title', trans('action.actions.quantity'))
            ->with('range', $range);
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request, $id)
    {
        if (!ScopedDocument::exist()) {
            abort(404);
        }
        
        $action = scopedAction()->getQuery()->findOrFail($id);
    
        $qty = (int) $request->get('quantity', 1);
        
        if ($qty > $action->available_qty) {
            $qty = $action->available_qty;
        }
        
        $document = ScopedDocument::getDocument();
        
        $action_products = $action->rActionProducts->keyBy('product_id');
        
        $gratis_products = ($action->isGratis()) ? $action->rGratisProducts->keyBy('product_id') : collect([]);
        
        if (is_null($document->action_id)) {
            $products = $this->getActionProducts(array_merge($action_products->pluck('product_id')->toArray(), $gratis_products->pluck('product_id')->toArray()));
            
            $document = $this->dbTransaction(function() use ($action, $qty, $action_products, $gratis_products, $products, $document) {
                $document->update([
                    'delivery_type' => $action->free_delivery ? 'free_delivery' : $document->delivery_type,
                    'delivery_cost' => $action->free_delivery ? 0 : $document->delivery_cost,
                ]);
                
                $discount_value2 = $this->setDocumentDiscountValue2($document, $action, $qty);
                
                $this->addDocumentProducts($document, $action_products, $gratis_products, $products, $qty, $discount_value2, $action);
                
                return $document->updateTotals();
            });
        } else {
            $document_products = $document->rDocumentProduct->keyBy('product_id');
            $document_gratis_products = $document->rDocumentGratisProducts->keyBy('product_id');
    
            $products = $this->getActionProducts(array_merge($document_products->pluck('product_id')->toArray(), $document_gratis_products->pluck('product_id')->toArray()));
            
            $document = $this->dbTransaction(function() use ($action, $document, $qty, $action_products, $gratis_products, $document_products, $document_gratis_products, $products) {
                $document->update([
                    'action_qty' => $qty,
                    'delivery_type' => $action->free_delivery ? 'free_delivery' : $document->delivery_type,
                    'delivery_cost' => $action->free_delivery ? 0 : $document->delivery_cost,
                ]);
                
                foreach ($action_products as $product_id => $action_product) {
                    if (isset($document_products[$product_id]) && isset($products[$product_id])) {
                        $document_product = $document_products[$product_id];
                        $product = $products[$product_id];
                        
                        $this->updateDocumentProduct($document, $qty, $action_product, $document_product, $action, $product);
                    }
                }
    
                foreach ($gratis_products as $product_id => $gratis_product) {
                    if (isset($document_gratis_products[$product_id]) && isset($products[$product_id])) {
                        $document_product = $document_gratis_products[$product_id];
                        $product = $products[$product_id];
            
                        $this->updateDocumentProduct($document, $qty, $gratis_product, $document_product, $action, $product);
                    }
                }
    
                return $document->updateTotals();
            });
        }
        
        return $this->getSuccessJsonResponse([
            'subtotal' => format_price($document->total_discounted_value).' '.ScopedDocument::currency(),
            'items' => $document->rDocumentProduct()->count(),
            'close_modal' => true,
            'notification' => [
                'type' => 'success',
                'message' => trans('action.notifications.quantity'),
            ],
            'redirect' => route('cart.index'),
        ]);
    }
    
    /**
     * @param \App\Document $document
     * @param \App\Action $action
     * @param int $qty
     * @return float|int
     */
    private function setDocumentDiscountValue2($document, $action, $qty)
    {
        $data = [
            'action_id' => $action->id,
            'action_qty' => $qty,
            'discount_value2' => 0,
        ];
        
        if ($action->isDiscount()) {
            $data['discount_value2'] = $document->useMpcPrice() ? $action->total_discount : $action->subtotal_discount;
        }
        
        $document->update($data);
        
        return $data['discount_value2'];
    }
    
    /**
     * @param \App\Document $document
     * @param \Illuminate\Database\Eloquent\Collection $actionProducts
     * @param \Illuminate\Database\Eloquent\Collection $gratisProducts
     * @param \Illuminate\Database\Eloquent\Collection $products
     * @param int $qty
     * @param float $discountValue2
     * @param \App\Action $action
     */
    private function addDocumentProducts($document, $actionProducts, $gratisProducts, $products, $qty, $discountValue2, $action)
    {
        foreach ($actionProducts as $product_id => $action_product) {
            if (isset($products[$product_id])) {
                $product = $products[$product_id];
                
                $this->addDocumentProduct($document, $action_product, $product, $qty, $discountValue2, 'regular', $action);
            }
        }
        
        foreach ($gratisProducts as $product_id => $gratis_product) {
            if (isset($products[$product_id])) {
                $product = $products[$product_id];
    
                $this->addDocumentProduct($document, $gratis_product, $product, $qty, $discountValue2, 'gratis', $action);
            }
        }
    }
    
    /**
     * @param \App\Document $document
     * @param \App\ActionProduct $actionProduct
     * @param \App\Product $product
     * @param int $qty
     * @param float $discountValue2
     * @param string $type
     * @param \App\Action $action
     * @return bool
     */
    private function addDocumentProduct($document, $actionProduct, $product, $qty, $discountValue2, $type, $action)
    {
        $quantity = $qty * $actionProduct->qty;
    
        $available_quantity = $product->qty - $quantity;
    
        if ($available_quantity >= 0) {
            $mpc = $actionProduct->prices['mpc'];
            $vpc = $actionProduct->prices['vpc'];
            
            if ($type == 'gratis') {
                $discount1 = 0;
                $discount2 = 0;
                $discount3_vpc = 0;
                $discount3_mpc = 0;
            } else {
                $discount1 = $document->payment_discount;
                $discount2 = $document->discount_value1;
                if ($action->isDiscount()) {
                    $discount3_vpc = $actionProduct->vpc_discount;
                    $discount3_mpc = $actionProduct->mpc_discount;
                } else {
                    $discount3_vpc = $discountValue2;
                    $discount3_mpc = $discountValue2;
                }
            }
            
            $mpc_discounted = calculateDiscount($mpc, $discount1, $discount2, $discount3_mpc);
            $vpc_discounted = calculateDiscount($vpc, $discount1, $discount2, $discount3_vpc);
        
            $data = [
                'client_id' => $document->client_id,
                'document_id' => $document->id,
                'product_id' => $product->id,
                'code' => $product->code,
                'barcode' => $product->barcode,
                'name' => $product->name,
                'unit_id' => $product->unit_id,
                'mpc' => $mpc,
                'mpc_discount' => $discount3_mpc,
                'mpc_discounted' => $mpc_discounted,
                'vpc' => $vpc,
                'vpc_discount' => $discount3_vpc,
                'vpc_discounted' => $vpc_discounted,
                'qty' => $quantity,
                'total' => $mpc * $quantity,
                'total_discounted' => $mpc_discounted * $quantity,
                'subtotal' => $vpc * $quantity,
                'subtotal_discounted' => $vpc_discounted * $quantity,
                'type' => $type,
            ];
        
            (new DocumentProduct())->add($data);
            
            return true;
        }
    
        $this->addErrors[] = "{$product->barcode}: Qty: {$quantity}: Av: {$available_quantity}";
    
        abort(403, 'Lager za proizvod :<br>'.implode('<br>', $this->addErrors));
        
        return false;
    }
    
    /**
     * @param \App\Document $document
     * @param int $qty
     * @param \App\ActionProduct $actionProduct
     * @param \App\DocumentProduct $documentProduct
     * @param \App\Action $action
     * @param \App\Product $product
     */
    private function updateDocumentProduct($document, $qty, $actionProduct, $documentProduct, $action, $product)
    {
        $quantity = $qty * $actionProduct->qty;
    
        $available_quantity = $product->qty - $quantity;
    
        if ($available_quantity >= 0) {
            $mpc = $documentProduct->mpc;
            $vpc = $documentProduct->vpc;
        
            if ($documentProduct->type == 'gratis') {
                $discount1 = 0;
                $discount2 = 0;
                $discount3_vpc = 0;
                $discount3_mpc = 0;
            } else {
                $discount1 = $document->payment_discount;
                $discount2 = $document->discount_value1;
                if ($action->isDiscount()) {
                    $discount3_vpc = $documentProduct->vpc_discount;
                    $discount3_mpc = $documentProduct->mpc_discount;
                } else {
                    $discount3_vpc = $document->discount_value2;
                    $discount3_mpc = $document->discount_value2;
                }
            }
            
            $mpc_discounted = calculateDiscount($mpc, $discount1, $discount2, $discount3_mpc);
            $vpc_discounted = calculateDiscount($vpc, $discount1, $discount2, $discount3_vpc);
            
            $documentProduct->update([
                'mpc_discounted' => $mpc_discounted,
                'vpc_discounted' => $vpc_discounted,
                'qty' => $quantity,
                'total' => $mpc * $quantity,
                'total_discounted' => $mpc_discounted * $quantity,
                'subtotal' => $vpc * $quantity,
                'subtotal_discounted' => $vpc_discounted * $quantity,
            ]);
            
            return true;
        }
    
        $this->addErrors[] = "{$product->barcode}: Qty: {$quantity}: Av: {$available_quantity}";
    
        abort(403, 'Lager za proizvod :<br>'.implode('<br>', $this->addErrors));
        
        return false;
    }
}
