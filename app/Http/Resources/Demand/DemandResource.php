<?php

namespace App\Http\Resources\Demand;

use App\Http\Resources\BaseResource;

/**
 * Class DemandResource
 *
 * @package App\Http\Resources\Demand
 */
class DemandResource extends BaseResource
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
