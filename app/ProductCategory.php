<?php

namespace App;

/**
 * Class ProductCategory
 *
 * @package App
 */
class ProductCategory extends Category
{
    /**
     * Products category root category id.
     */
    const PRODUCT_CATEGORY_ID = 10;
    
    /**
     * @inheritDoc
     */
    public function getCategoryTree($root = 10)
    {
       return parent::getCategoryTree(self::PRODUCT_CATEGORY_ID)->reject(function($category, $key) {
            return $category->id == self::PRODUCT_CATEGORY_ID;
        })->values();
    }
}
