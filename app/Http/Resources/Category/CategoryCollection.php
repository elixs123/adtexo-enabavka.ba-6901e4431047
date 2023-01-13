<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\BaseCollection;

/**
 * Class CategoryCollection
 *
 * @package App\Http\Resources\Category
 */
class CategoryCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [self::$wrap => CategoryResource::collection($this->collection)];
    }
}
