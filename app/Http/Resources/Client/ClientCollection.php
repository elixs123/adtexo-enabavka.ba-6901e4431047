<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\BaseCollection;

/**
 * Class ClientCollection
 *
 * @package App\Http\Resources\Client
 */
class ClientCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [self::$wrap => ClientResource::collection($this->collection)];
    }
}
