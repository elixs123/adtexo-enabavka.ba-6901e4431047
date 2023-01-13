<?php

namespace App\Http\Resources\Billing;

use App\Http\Resources\BaseResource;

/**
 * Class BillingResource
 *
 * @package App\Http\Resources\Billing
 */
class BillingResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
