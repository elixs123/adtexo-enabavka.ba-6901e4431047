<?php

namespace App\Http\Resources\City;

use App\Http\Resources\BaseCollection;

/**
 * Class CityCollection
 *
 * @package App\Http\Resources\City
 */
class CityCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [self::$wrap => CityResource::collection($this->collection)];
    }
}
