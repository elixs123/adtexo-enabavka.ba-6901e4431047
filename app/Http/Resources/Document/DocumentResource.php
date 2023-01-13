<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\BaseResource;
use App\Http\Resources\CodeBook\CodeBookResource;
use App\Http\Resources\Stock\StockResource;
use App\Http\Resources\DocumentProduct\DocumentProductCollection;

/**
 * Class DocumentResource
 *
 * @package App\Http\Resources
 */
class DocumentResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $client_is_headquarter = $this->rClient->is_headquarter;
        
        $client = $client_is_headquarter ? $this->rClient : $this->rClient->rHeadquarter;
        
        return [
			'id' => $this->id,
			'parent_id' => $this->parent_id,
			'created_by' => $this->created_by,
			'client_id' => $this->client_id,
			'stock_id' => $this->stock_id,
			'buyer_data' => $this->buyer_data,
			'shipping_data' => $this->shipping_data,
			'type_id' => $this->type_id,
			'status' => $this->status,
			'internal_status' => $this->internal_status,
			'sync_status' => $this->sync_status,
			'payment_type' => $this->payment_type,
			'payment_period' => $this->payment_period,
			'delivery_type' => $this->delivery_type,
			'delivery_cost' => $this->delivery_cost,
			'subtotal' => $this->subtotal,
			'subtotal_discounted' => $this->subtotal_discounted,
			'total' => $this->total,
			'tax_rate' => $this->tax_rate,
			'discount_value1' => $this->discount_value1,
			'discount_value2' => $this->discount_value2,
			'payment_discount' => $this->payment_discount,
			'total_discounted' => $this->total_discounted,
			'parent_subtotal' => $this->parent_subtotal,
			'currency' => $this->currency,
			'date_of_order' => (String) $this->date_of_order,
			'date_of_warehouse' => (String) $this->date_of_warehouse,
			'date_of_processing' => (String) $this->date_of_processing,
			'date_of_delivery' => (String) $this->date_of_delivery,
			'date_of_payment' => (String) $this->date_of_payment,
			'date_of_sync' => (String) $this->date_of_sync,
			'note' => $this->note,
			'note_express_post' => $this->note_express_post,
			'package_number' => $this->package_number,
			'weight' => $this->weight,
			'created_at' => (String) $this->created_at,
			'updated_at' => (String) $this->updated_at,
			'fiscal_receipt_no' => $this->fiscal_receipt_no,
			'fiscal_receipt_datetime' => $this->fiscal_receipt_datetime,
			'fiscal_receipt_amount' => $this->fiscal_receipt_amount,
			'fiscal_receipt_void_no' => $this->fiscal_receipt_void_no,
			'fiscal_receipt_void_datetime' => $this->fiscal_receipt_void_datetime,
			'fiscal_receipt_void_amount' => $this->fiscal_receipt_void_amount,
			'fiscal_net_price' => $this->fiscal_net_price,
			'fiscal_gross_price' => $this->fiscal_gross_price,
			'fiscal_discounted_price' => $this->fiscal_discounted_price,
			'fiscal_vat' => $this->fiscal_vat,
			'fiscal_discount_percent' => $this->fiscal_discount_percent,
			
            'fiscal_delivery_net_price' => $this->fiscal_delivery_net_price,
            'fiscal_delivery_gross_price' => $this->fiscal_delivery_gross_price,
            'fiscal_delivery_price' => $this->fiscal_delivery_price,
			
			'created_by_data' => $this->rCreatedBy->rPerson,
			'clinet_data' => $client,
			'location_data' => $this->rClient,
			'currency_data' => new CodeBookResource($this->rCurrency),
			'stock_data' => new StockResource($this->rStock),
			'status_data' => new CodeBookResource($this->rStatus),
			'type_data' => new CodeBookResource($this->rType),
			'payment_type_data' => new CodeBookResource($this->rPaymentType),
			'payment_period_data' => new CodeBookResource($this->rPaymentPeriod),
			'delivery_type_data' => new CodeBookResource($this->rDeliveryType),
			
			'products' => new DocumentProductCollection($this->rDocumentProduct)
		];
    }
}
