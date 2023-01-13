<?php

namespace App\Http\Resources\Stock;

use App\Http\Resources\BaseCollection;

/**
 * Class StockCollection
 *
 * @package App\Http\Resources\Stock
 */
class StockCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [self::$wrap => StockResource::collection($this->collection)];
    }
}
