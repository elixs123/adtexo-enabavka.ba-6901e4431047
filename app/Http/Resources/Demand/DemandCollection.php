<?php

namespace App\Http\Resources\Demand;

use App\Http\Resources\BaseCollection;

/**
 * Class DemandCollection
 *
 * @package App\Http\Resources\Demand
 */
class DemandCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [self::$wrap => DemandResource::collection($this->collection)];
    }
}
