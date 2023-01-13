<?php

namespace App\Http\Resources\Brand;

use App\Http\Resources\BaseResource;

/**
 * Class BrandResource
 *
 * @package App\Http\Resources
 */
class BrandResource extends BaseResource
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
			'name' => $this->name,
            'status' => $this->status,
            'priority' => $this->priority,
		];
    }
}