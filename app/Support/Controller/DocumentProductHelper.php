<?php

namespace App\Support\Controller;

use App\Document;
use App\DocumentProduct;
use App\Product;
use Illuminate\Support\Facades\DB;
use App\Support\Scoped\ScopedContractFacade as ScopedContract;

/**
 * Trait DocumentProductHelper
 *
 * @package App\Support\Controller
 */
trait DocumentProductHelper
{
    /**
     * @param \App\Document $document
     * @param \App\Product|mixed $product
     * @param int $quantity
     * @return \App\Document
     */
    protected function addDocumentProduct($document, $product, $quantity)
    {
        if (!is_null($product)) {
            $availableQuantity = $product->qty - $quantity;
    
            if (($availableQuantity >= 0) || $document->isReturn() || $document->isPreOrder()) {
                if(($product->is_gratis == 1) && !$document->isReturn() && !$document->isPreOrder()) {
                    $product->price->mpc = 0;
                    $product->price->vpc = 0;
                }
                
                if (ScopedContract::hasContract() && ScopedContract::hasProduct($product->id)) {
                    $mpc = calculateDiscount($product->price->mpc, ScopedContract::getProduct($product->id)->discount);
                    $vpc = calculateDiscount($product->price->vpc, ScopedContract::getProduct($product->id)->discount);
                } else {
                    $mpc = $product->price->mpc;
                    $vpc = $product->price->vpc;
                }
                
                $mpc_discounted = calculateDiscount($mpc, $document->discount1, $document->discount2, $product->price->mpc_discount);
                $vpc_discounted = calculateDiscount($vpc, $document->discount1, $document->discount2, $product->price->vpc_discount);
    
                $data = [
                    'client_id' => $document->client_id,
                    'contract_id' => ScopedContract::id(),
                    'document_id' => $document->id,
                    'product_id' => $product->id,
                    'contract_discount' => (ScopedContract::hasContract() && ScopedContract::hasProduct($product->id)) ? ScopedContract::getProduct($product->id)->discount : 0,
                    'code' => $product->code,
                    'barcode' => $product->barcode,
                    'luceed_uid' => $product->luceed_uid,
                    'name' => $product->name,
                    'unit_id' => $product->unit_id,
                    'mpc' => $product->price->mpc,
                    'vpc' => $product->price->vpc,
                    'mpc_discounted' => $mpc_discounted,
                    'vpc_discounted' => $vpc_discounted,
                    'loyalty_points' => ($document->isReturn() ? -1 : 1) * $product->loyalty_points,
                    'qty' => $quantity,
                    'total' => $mpc * $quantity,
                    'total_discounted' => $mpc_discounted * $quantity,
                    'subtotal' => $vpc * $quantity,
                    'subtotal_discounted' => $vpc_discounted * $quantity,
                    'discount1' => $document->discount1,
                    'discount2' => $document->discount2,
                    'discount3' => $document->useMpcPrice() ? $product->price->mpc_discount : $product->price->vpc_discount,
                    'total_loyalty_points' => (($document->isReturn() ? -1 : 1) * $product->loyalty_points) * $quantity,
                ];
                
                if ($product->is_promo_product) {
                    $data['promo_children'] = $this->parsePromoProducts($product->rPromoItems, $quantity);
                }
    
                (new DocumentProduct())->add($data);
            }
        }
    
        return $document->updateTotals();
    }
    
    /**
     * @param \Illuminate\Support\Collection|mixed $promoProducts
     * @param int $quantity
     * @return array|null
     */
    protected function parsePromoProducts($promoProducts, $quantity)
    {
        $products = $promoProducts->map(function ($product) use ($quantity) {
            return [
                'product_id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'barcode' => $product->barcode,
                'unit_id' => $product->unit_id,
                'qty' => (int) $product->pivot->promo_qty * $quantity,
            ];
        })->values()->toArray();
        
        if (isset($products[0])) {
            return $products;
        }
        
        return null;
    }
    
    /**
     * @param \App\Document $document
     * @param \App\DocumentProduct $documentProduct
     * @param int $quantity
     * @return \App\Document
     */
    protected function updateDocumentProduct($document, $documentProduct, $quantity)
    {
        $mpc = calculateDiscount($documentProduct->mpc, $documentProduct->contract_discount);
        $vpc = calculateDiscount($documentProduct->vpc, $documentProduct->contract_discount);
        
        $mpc_discounted = calculateDiscount($mpc, $documentProduct->discount1, $documentProduct->discount2, $documentProduct->discount3);
        $vpc_discounted = calculateDiscount($vpc, $documentProduct->discount1, $documentProduct->discount2, $documentProduct->discount3);
        
        $data = [
            'qty' => $quantity,
            'total' => $quantity * $mpc,
            'total_discounted' => $quantity * $mpc_discounted,
            'subtotal' => $quantity * $vpc,
            'subtotal_discounted' => $quantity * $vpc_discounted,
            'total_loyalty_points' => $documentProduct->loyalty_points * $quantity,
        ];
    
        $documentProduct->update($data);
    
        return $document->updateTotals();
    }
}
