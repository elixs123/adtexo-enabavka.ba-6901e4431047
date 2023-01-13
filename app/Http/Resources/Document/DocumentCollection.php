<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\BaseCollection;

/**
 * Class DocumentCollection
 *
 * @package App\Http\Resources\Fantasy\Document
 */
class DocumentCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [self::$wrap => DocumentResource::collection($this->collection)];
    }
}
