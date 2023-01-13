<?php

namespace App\Http\Resources\Person;

use App\Http\Resources\BaseCollection;

/**
 * Class PersonCollection
 *
 * @package App\Http\Resources\Person
 */
class PersonCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [self::$wrap => PersonResource::collection($this->collection)];
    }
}
