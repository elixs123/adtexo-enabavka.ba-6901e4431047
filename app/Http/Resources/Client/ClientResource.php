<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\BaseResource;
use App\Http\Resources\CodeBook\CodeBookResource;
use App\Http\Resources\Stock\StockResource;
use App\Http\Resources\DocumentProduct\DocumentProductCollection;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ClientResource
 *
 * @package App\Http\Resources
 */
class ClientResource extends BaseResource
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
			'parent_id' => $this->parent_id,
            'jib' => $this->jib,
            'pib' => $this->pib,
            'code' => $this->code,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country_id' => $this->country_id,
            'country' => new CodeBookResource($this->rCountry),
            'is_location' => $this->is_location,
            'location_code' => $this->location_code,
            'location_name' => $this->location_name,
            'location_type_id' => $this->location_type_id,
            'category_id' => $this->category_id,
            'category' => new CodeBookResource($this->rCategory),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'phone' => $this->phone,
            'note' => $this->note,
            'payment_discount' => $this->payment_discount,
            'discount_value1' => $this->discount_value1,
            'discount_value2' => $this->discount_value2,
            'stock' => $this->rStock,
            'lang_id' => $this->lang_id,
            'status' => new CodeBookResource($this->rStatus),
            'type' => new CodeBookResource($this->rType),
            'payment_period' => new CodeBookResource($this->rPaymentPeriod),
            'payment_type' => new CodeBookResource($this->rPaymentType),
            'created_at' => (String) $this->created_at
		];
    }
}