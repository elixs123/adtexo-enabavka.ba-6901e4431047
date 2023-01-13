<?php

namespace App\Http\Controllers\Document;

use App\Document;
use App\DocumentChange;
use App\DocumentProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\Product\AddDocumentProductRequest;
use App\Product;
use App\Support\Controller\DocumentProductHelper;
use App\Support\Scoped\ScopedContractFacade as ScopedContract;
use App\Support\Scoped\ScopedStockFacade as ScopedStock;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers\Document
 */
class ProductController extends Controller
{
    use DocumentProductHelper;
    
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * @var \App\DocumentProduct
     */
    private $documentProduct;
    
    /**
     * @var \App\DocumentChange
     */
    private $documentChange;
    
    /**
     * @var \App\Product
     */
    private $product;
    
    /**
     * ProductController constructor.
     *
     * @param \App\Document $document
     * @param \App\DocumentProduct $documentProduct
     * @param \App\DocumentChange $documentChange
     * @param \App\Product $product
     */
    public function __construct(Document $document, DocumentProduct $documentProduct, DocumentChange $documentChange, Product $product)
    {
        $this->document = $document;
        $this->documentProduct = $documentProduct;
        $this->documentChange = $documentChange;
        $this->product = $product;
    }
    
    /**
     * @param int $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function show($id)
    {
        $item = $this->document->getOne($id);
    
        if (is_null($item)) {
            abort(404, trans('document.errors.not_found', ['id' => $id]));
        }
        
        return view('document.product.show')->with([
            'item' => $item,
            'method' => 'post',
            'form_url' => route('document.product.add', ['id' => $id]),
            'form_title' => trans('document.actions.add.product'),
            'exclude' => request('e'),
        ]);
    }
    
    /**
     * @param \App\Http\Requests\Document\Product\AddDocumentProductRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(AddDocumentProductRequest $request, $id)
    {
        $item = $this->document->getOne($id);
    
        if (is_null($item)) {
            abort(404, trans('document.errors.not_found', ['id' => $id]));
        }
    
        ScopedContract::setDocument($item);
    
        $product_id = $request->get('product_id');
        $qty = (int) $request->get('qty');
    
        $documentProduct = $this->documentProduct->getOneByProdcutIdAndDocumentId($product_id, $id);
    
        $product = is_null($documentProduct) ? $this->product->relation([], true)->getOne($product_id) : null;
    
        $currency = is_null($documentProduct) ? ScopedStock::currency() : null;
        
        $this->dbTransaction(function() use ($item, $documentProduct, $product, $qty) {
            if (is_null($documentProduct)) {
                $this->addDocumentProduct($item, $product, $qty);
            } else {
                $this->updateDocumentProduct($item, $documentProduct, $qty + $documentProduct->qty);
            }
            
            $this->documentChange->add([
                'document_id' => $item->id,
                'changed_by' => $this->getUserId(),
                'product_id' => $product->id,
                'type' => 'quantity',
                'value' => is_null($documentProduct) ? 0 : $documentProduct->qty,
                'created_at' => $now = now()->toDateTimeString(),
                'updated_at' => $now,
            ]);
        });
    
        return $this->getSuccessJsonResponse([
            'notification' => [
                'type' => 'success',
                'message' => trans('document.notifications.added'),
            ],
        ]);
    }
}
