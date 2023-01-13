<?php

namespace App\Http\Controllers\Cron;

use App\Document;
use App\Http\Controllers\Controller;
use App\ProductQuantity;

/**
 * Class DocumentController
 *
 * @package App\Http\Controllers\Cron
 */
class DocumentController extends Controller
{
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * DocumentController constructor.
     *
     * @param \App\Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function productQuantities()
    {
        $this->document->statusId = 'canceled';
        $this->document->typeId = 'order';
        $this->document->limit = null;
        $this->document->includeIds = [15891, 15892, 15893, 15894, 15896, 15897, 15898, 15899, 15900, 15901, 15902];
        $documents = $this->document->relation(['rDocumentProduct', 'rDocumentGratisProducts', 'rAction'])->getAll();
        
        $this->dbTransaction(function () use ($documents) {
            foreach ($documents as $document) {
                foreach ($document->rDocumentProduct as $product) {
                    ProductQuantity::decrementReservedQty($document->stock_id, $product->product_id, $product->qty);
                }
        
                if ($document->isAction()) {
                    $document->rAction->decrementReservedQty($document->action_qty);
                    
                    foreach ($document->rDocumentGratisProducts as $product) {
                        ProductQuantity::decrementReservedQty($document->stock_id, $product->product_id, $product->qty);
                    }
                }
            }
        });
        
        return response()->json($documents->toArray());
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function documentTotal()
    {
        $this->document->limit = null;
        $this->document->includeIds = [14920];
        $documents = $this->document->getAll();
        
        foreach ($documents as $document) {
            $document->updateTotals();
        }
    
        return response()->json($documents->toArray());
    }
}
