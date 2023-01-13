<?php

namespace App\Http\Resources\Brand;

use App\Http\Resources\BaseCollection;

/**
 * Class BrandCollection
 *
 * @package App\Http\Resources\Brand
 */
class BrandCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [self::$wrap => BrandResource::collection($this->collection)];
    }
}
