<?php

namespace App\Http\Resources\Stock;

use App\Http\Resources\BaseResource;

/**
 * Class StockResource
 *
 * @package App\Http\Resources
 */
class StockResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
			'id' => $this->id,
			'code' => $this->code,
			'currency' => $this->currency,
			'name' => $this->name,
			'address' => $this->address,
			'city' => $this->city,
			'postal_code' => $this->postal_code,
			'country_id' => $this->country_id,
			'tax_rate' => $this->tax_rate,
			
		];
    }
}