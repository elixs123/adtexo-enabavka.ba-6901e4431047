<?php

namespace App\Http\Resources\DocumentProduct;

use App\Http\Resources\BaseResource;
use App\Http\Resources\CodeBook\CodeBookResource;
use App\Http\Resources\Category\CategoryResource;

/**
 * Class DocumentProductResource
 *
 * @package App\Http\Resources
 */
class DocumentProductResource extends BaseResource
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
			'product_id' => $this->product_id,
			'code' => $this->code,
			'barcode' => $this->barcode,
			'name' => $this->name,
			'unit_id' => new CodeBookResource($this->rUnit),
			'price' => $this->price,
			'price_discounted' => $this->price_discounted,
			'qty' => $this->qty,
			'mpc' => $this->mpc,
			'mpc_discount' => $this->mpc_discount,
			'mpc_discounted' => $this->mpc_discounted,
			'vpc' => $this->vpc,
			'vpc_discount' => $this->vpc_discount,
			'vpc_discounted' => $this->vpc_discounted,
			'subtotal' => $this->subtotal,
			'subtotal_discounted' => $this->subtotal_discounted,
			'total' => $this->total,
			'total_discounted' => $this->total_discounted,
			'total_value' => $this->total_value,
			'total_discounted_value' => $this->total_discounted_value,
			'fiscal_net_price' => $this->fiscal_net_price,
			'fiscal_net_discounted_price' => $this->fiscal_net_discounted_price,
			'fiscal_gross_price' => $this->fiscal_gross_price,
			'fiscal_discounted_price' => $this->fiscal_discounted_price,
			'fiscal_discount_percent' => $this->fiscal_discount_percent,
			'father_category' => isset($this->rProduct->category->rFatherCategory) ? new CategoryResource($this->rProduct->category->rFatherCategory) : null,
			'category' => isset($this->rProduct->rCategory) ? new CategoryResource($this->rProduct->rCategory) : null
		];
    }
}
