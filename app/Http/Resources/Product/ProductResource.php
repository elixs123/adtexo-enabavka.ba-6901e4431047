<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\CodeBook\CodeBookResource;
use App\Product;


/**
 * Class ProductResource
 *
 * @package App\Http\Resources
 */
class ProductResource extends BaseResource
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
            'lang_id' => $this->lang_id,
            'name' => $this->translation->name,
            'text' => $this->translation->text,
            'barcode' => $this->barcode,
            'brand' => new BrandResource($this->brand),
            'category' => new CategoryResource($this->rCategory),
            'video' => $this->video,
            'status' => new CodeBookResource($this->rStatus),
            'rang' => $this->rang,
            'unit' => new CodeBookResource($this->rUnit),
            'packing' => $this->packing,
            'transport_packaging' => $this->transport_packaging,
            'palette' => $this->palette,
            'weight' => $this->weight,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'loyalty_points' => $this->loyalty_points,
            'is_gratis' => $this->is_gratis,
            'prices' => $this->rProductPrices,
            'promo' => (intval($this->category_id) == Product::PROMO_CATEGORY_ID) ? $this->rPromoItems()->get()->toArray() : [],
		];
    }
}
