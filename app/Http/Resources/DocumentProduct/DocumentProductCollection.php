<?php

namespace App\Http\Resources\DocumentProduct;

use App\Http\Resources\BaseCollection;

/**
 * Class DocumentProductCollection
 *
 * @package App\Http\Resources\Fantasy\DocumentProduct
 */
class DocumentProductCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return DocumentProductResource::collection($this->collection);
    }
}
