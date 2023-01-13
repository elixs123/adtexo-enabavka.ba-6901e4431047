<?php

namespace App\Http\Resources\City;

use App\Http\Resources\BaseResource;
use App\Http\Resources\CodeBook\CodeBookResource;

/**
 * Class CityResource
 *
 * @package App\Http\Resources\City
 */
class CityResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['r_status'] = new CodeBookResource($this->whenLoaded('rStatus'));
        $data['r_country'] = new CodeBookResource($this->whenLoaded('rCountry'));
        
        return $data;
    }
}
