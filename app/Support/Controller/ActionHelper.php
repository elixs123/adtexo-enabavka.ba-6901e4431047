<?php

namespace App\Support\Controller;

use App\Product;
use App\Support\Scoped\ScopedStockFacade as ScopedStock;

/**
 * Trait ActionHelper
 *
 * @package App\Support\Controller
 */
trait ActionHelper
{
    /**
     * @return \App\Product
     */
    private function getProductModel()
    {
        $product = new Product();
        $product->limit = null;
        // $product->langId = ScopedStock::langId();
        $product->statusId = 'active';
        
        return $product;
    }
    
    /**
     * @param array $productIds
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getActionProducts(array $productIds)
    {
        $product = $this->getProductModel();
        $product->productIds = $productIds;
        
        return $product->getAll()->keyBy('id');
    }
}
