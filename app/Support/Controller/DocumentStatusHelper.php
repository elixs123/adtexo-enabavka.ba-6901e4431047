<?php

namespace App\Support\Controller;

use App\ProductQuantity;
use Illuminate\Support\Facades\DB;

/**
 * Trait DocumentStatusHelper
 *
 * @package App\Support\Controller
 */
trait DocumentStatusHelper
{
    /**
     * @param \App\Document $document
     */
    private function updateStock($document)
    {
        $attributes = $contractAttributes = [];
        
        $this->removeFromStock($document, $attributes, $contractAttributes);
        
        $this->returnToStock($document, $attributes, $contractAttributes);
        
        if (!empty($attributes)) {
            DB::table('product_stocks')->insert($attributes);
            
            foreach ($attributes as $data) {
                ProductQuantity::updateAvailableQty($data['stock_id'], $data['product_id']);
                
                if (($data['action'] == 'remove') && ($data['qty'] < 0)) {
                    ProductQuantity::decrementReservedQty($data['stock_id'], $data['product_id'], abs($data['qty']));
                }
            }
        }
        
        if (!empty($contractAttributes)) {
            foreach ($contractAttributes as $data) {
                $queryC = DB::table('contracts')->where('id', $data['contract_id']);
                
                $queryCP = DB::table('contract_products')->where('contract_id', $data['contract_id'])->where('product_id', $data['product_id']);
                
                if ($data['action'] == 'remove') {
                    $queryC->increment('total_bought', abs($data['bought']));
                    $queryCP->increment('bought', abs($data['bought']));
                } else if ($data['action'] == 'return') {
                    $queryC->decrement('total_bought', abs($data['bought']));
                    $queryCP->decrement('bought', abs($data['bought']));
                }
            }
        }
        
        $this->resoleReservedQty($document);
    }
    
    /**
     * @param \App\Document $document
     * @param array $data
     * @param array $contractAttributes
     */
    private function removeFromStock($document, &$data, &$contractAttributes)
    {
        if (($document->type_id == 'order') && ($document->status == 'invoiced')) {
            $now = now()->toDateTimeString();
            
            $products = $document->rDocumentProduct->filter(function($product) {
                return is_null($product->contract_id);
            });
            
            foreach ($products as $product) {
                $data[] = [
                    'product_id' => $product->product_id,
                    'stock_id' => $document->stock_id,
                    'qty' => -abs($product->qty),
                    'action' => 'remove',
                    'note' => '#' . $document->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            
            if ($document->isAction() && $document->rAction->isGratis()) {
                $products = $document->rDocumentGratisProducts->filter(function($product) {
                    return is_null($product->contract_id);
                });
                
                foreach ($products as $product) {
                    $data[] = [
                        'product_id' => $product->product_id,
                        'stock_id' => $document->stock_id,
                        'qty' => -abs($product->qty),
                        'action' => 'remove',
                        'note' => '#' . $document->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            
            $products = $document->rDocumentProduct->reject(function($product) {
                return is_null($product->contract_id);
            });
            
            foreach ($products as $product) {
                $contractAttributes[] = [
                    'contract_id' => $product->contract_id,
                    'product_id' => $product->product_id,
                    'bought' => abs($product->qty),
                    'action' => 'remove',
                ];
            }
        }
    }
    
    /**
     * @param \App\Document $document
     * @param array $data
     * @param array $contractAttributes
     */
    private function returnToStock($document, &$data, &$contractAttributes)
    {
        if (
            (in_array($document->type_id, ['return', 'reversal']) && ($document->status == 'completed')) ||
            (($document->type_id == 'order') && ($document->status == 'reversed'))
        ) {
            $now = now()->toDateTimeString();
            
            $products = $document->rDocumentProduct->filter(function($product) {
                return is_null($product->contract_id);
            });
            
            foreach ($products as $product) {
                $data[] = [
                    'product_id' => $product->product_id,
                    'stock_id' => $document->stock_id,
                    'qty' => abs($product->qty),
                    'action' => 'return',
                    'note' => '#' . $document->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            
            $products = $document->rDocumentProduct->reject(function($product) {
                return is_null($product->contract_id);
            });
            
            foreach ($products as $product) {
                $contractAttributes[] = [
                    'contract_id' => $product->contract_id,
                    'product_id' => $product->product_id,
                    'bought' => abs($product->qty),
                    'action' => 'return',
                ];
            }
        }
    }
    
    /**
     * @param \App\Document $document
     */
    private function resoleReservedQty($document)
    {
        if (($document->type_id == 'order') && ($document->status == 'canceled')) {
            $products = $document->rDocumentProduct->filter(function($product) {
                return is_null($product->contract_id);
            });
            
            foreach ($products as $product) {
                ProductQuantity::resolveReservedQty($document->stock_id, $product->product_id, abs($product->qty));
            }
        }
        
        if ($document->isAction() && ($document->isOrder() || $document->isCash()) && ($document->status == 'invoiced')) {
            $document->rAction->decrementReservedQty($document->action_qty);
            
            $document->rAction->incrementBoughtQty($document->action_qty);
            
            $products = $document->rDocumentGratisProducts->filter(function($product) {
                return is_null($product->contract_id);
            });
            
            foreach ($products as $product) {
                ProductQuantity::resolveReservedQty($document->stock_id, $product->product_id, abs($product->qty));
            }
        }
    }
}
