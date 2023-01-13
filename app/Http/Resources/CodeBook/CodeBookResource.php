<?php

namespace App\Http\Resources\CodeBook;

use App\Http\Resources\BaseResource;

/**
 * Class CodeBookResource
 *
 * @package App\Http\Resources
 */
class CodeBookResource extends BaseResource
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
			'name' => $this->name,
		];
    }
}
