<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\BaseCollection;

/**
 * Class ProductCollection
 *
 * @package App\Http\Resources\Product
 */
class ProductCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [self::$wrap => ProductResource::collection($this->collection)];
    }
}
