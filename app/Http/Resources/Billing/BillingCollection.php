<?php

namespace App\Http\Resources\Billing;

use App\Http\Resources\BaseCollection;

/**
 * Class BillingCollection
 *
 * @package App\Http\Resources\Billing
 */
class BillingCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [self::$wrap => BillingResource::collection($this->collection)];
    }
}
