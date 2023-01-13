<?php

namespace App\Support\Controller;

use App\Document;
use App\DocumentProduct;
use App\Product;
use App\Support\Scoped\ScopedDocumentFacade;

/**
 * Trait DocumentHelper
 *
 * @package App\Support\Controller
 */
trait DocumentHelper
{
    /**
     * @param \App\BaseModel|\App\Document $document
     * @return \App\BaseModel|\App\Document
     */
    protected function completeDocument($document)
    {
        if ($document->isPreOrder() || $document->isCash()) {
            $order = $this->createOrderFromPreOrder($document);
    
            $this->copyProducts($document->rDocumentProduct, $order, $document->isPreOrder(), $document->rDocumentGratisProducts);
    
            $this->completePreOrder($document);
    
            if ($document->isPreOrder()) {
                $order->updateTotals();
            }
    
            ScopedDocumentFacade::open($order->id);
            
            return $order;
        } else if ($document->isOrder()) {
            return $document;
        } else {
            $this->completePreOrder($document, $document->isReturn() ? 'in_process' : 'completed');
    
            return $document;
        }
    }
    
    /**
     * @param \App\Document $document
     * @return \App\BaseModel|\App\Document
     */
    protected function createOrderFromPreOrder($document)
    {
        $attributes = Document::prepareForCopy($document->toArray(), [
            'parent_id' => $document->id,
            'type_id' => 'order',
            'parent_subtotal' => $document->subtotal,
            // 'date_of_order' => now()->toDateString(),
            'date_of_payment' => null, //now()->addDays(array_get($document->buyer_data, 'payment_period_in_days', 0))->toDateString(),
        ]);
        
        return Document::create($attributes);
    }
    
    /**
     * @param \Illuminate\Database\Eloquent\Collection $products
     * @param \App\Document $document
     * @param bool $checkAvailableQty
     * @param \Illuminate\Database\Eloquent\Collection $gratisProducts
     */
    protected function copyProducts($products, $document, $checkAvailableQty = false, $gratisProducts = [])
    {
        $data = [];
        foreach ($products as $documentProduct) {
            if ($checkAvailableQty) {
                if (!$this->productIsAvailable($documentProduct)) {
                    continue;
                }
            }
            
            $data[] = DocumentProduct::prepareForCopy($documentProduct->toArray(), ['document_id']);
        }
        
        $gratis = [];
        foreach ($gratisProducts as $documentProduct) {
            if ($checkAvailableQty) {
                if (!$this->productIsAvailable($documentProduct)) {
                    continue;
                }
            }

            $gratis[] = DocumentProduct::prepareForCopy($documentProduct->toArray(), ['document_id']);
        }
        
        if (isset($data[0])) {
            $document->rDocumentProduct()->createMany($data);
        }
        
        if (isset($gratis[0])) {
            $document->rDocumentGratisProducts()->createMany($gratis);
        }
    }
    
    /**
     * @param \App\DocumentProduct|mixed $documentProduct
     * @return bool
     */
    private function productIsAvailable($documentProduct)
    {
        if (is_null($documentProduct->rProduct)) {
            return false;
        } else {
            $availableQuantity = $documentProduct->rProduct->qty - $documentProduct->qty;
            if ($availableQuantity < 0) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @param \App\Document $document
     * @param string $status
     */
    protected function completePreOrder($document, $status = 'completed')
    {
        $document->update([
            'status' => $status,
        ]);
        
        ScopedDocumentFacade::close();
    }
    
    /**
     * @param \App\Document $document
     * @return \App\BaseModel|\App\Document
     */
    protected function copyDocument($document)
    {
        $client = $document->rClient;
        
        $attributes = Document::prepareForCopy($document->toArray(), [
            'parent_id' => null,
            'stock_id' => $client->stock_id,
            'buyer_data' => $client->toArray(),
            'shipping_data' => null,
            'status' => 'draft',
            'sync_status' => null,
            'parent_subtotal' => 0,
            'date_of_order' => now()->toDateString(),
            'date_of_warehouse' => null,
            'date_of_processing' => null,
            'date_of_delivery' => null,
            'date_of_payment' => null, //now()->addDays($client->payment_period_in_days)->toDateString(),
            'delivery_date' => null,
            'date_of_sync' => null,
            'package_number' => null,
            'weight' => null,
            'fiscal_receipt_no' => null,
            'fiscal_receipt_datetime' => null,
            'fiscal_receipt_amount' => null,
            'fiscal_receipt_void_no' => null,
            'fiscal_receipt_void_datetime' => null,
            'fiscal_receipt_void_amount' => null,
            'is_payed' => 0,
            'payed_at' => null,
        ]);
        
        return Document::create($attributes);
    }
    
    /**
     * @param null|int|array $createdBy
     * @param null|array $dates
     * @param null|array|string $typeId
     * @param null|array|string $statusId
     * @param null|int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getUserDocuments($createdBy = null, $dates = null, $typeId = null, $statusId = null, $limit = null)
    {
        $document_types = get_codebook_opts('document_type')->keyBy('code')->toArray();
        
        $document = new Document();
        $document->createdBy = $createdBy;
        $document->dateOfOrder = $dates;
        $document->typeId = $typeId;
        $document->statusId = $statusId;
        $document->limit = $limit;
    
        return $document->getAll()->reject(function($document) {
            return $document->rClient->status == 'inactive';
        })->transform(function($document) use ($document_types) {
            $document->type = [
                'name' => $document_types[$document->type_id]['name'],
                'background_color' => $document_types[$document->type_id]['background_color'],
                'color' => $document_types[$document->type_id]['color'],
            ];
            
            return $document;
        });
    }
    
    /**
     * @param \App\Document $document
     * @param null|integer|array $createdBy
     */
    protected function scopeDocumentCreatedBy(&$document, $createdBy = null)
    {
        $document->createdBy = (userIsSalesman() || userIsFocuser()) ? $this->getUserId() : $createdBy;
    }
}
