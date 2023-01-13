<?php

namespace App\Http\Controllers\Document;

use App\Document;
use App\DocumentProduct;
use App\Http\Controllers\Controller;
use App\Support\Controller\DocumentHelper;
use App\Support\Scoped\ScopedDocumentFacade;

/**
 * Class CopyController
 *
 * @package App\Http\Controllers\Document
 */
class CopyController extends Controller
{
    use DocumentHelper;
    
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * @var \App\DocumentProduct
     */
    private $documentProduct;
    
    /**
     * CopyController constructor.
     *
     * @param \App\Document $document
     * @param \App\DocumentProduct $documentProduct
     */
    public function __construct(Document $document, DocumentProduct $documentProduct)
    {
        $this->document = $document;
        $this->documentProduct = $documentProduct;
    }
    
    /**
     * Copy.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function copy($id)
    {
        if (userIsSalesman()) {
            $this->document->createdBy = $this->getUserId();
        }
        $document = $this->document->getOne($id);
        
        if (is_null($document)) {
            return $this->getErrorJsonResponse(trans('document.errors.not_found', ['id' => $id]), 404);
        }
    
        $copied_document = $this->dbTransaction(function() use ($document) {
            $copied_document = $this->copyDocument($document);
            
            $this->copyProducts($document->rDocumentProduct, $copied_document, false, $document->rDocumentGratisProducts);
            
            return $copied_document;
        });
    
        ScopedDocumentFacade::open($copied_document->id);
        
        return $this->getSuccessJsonResponse([
            'redirect' => route('document.show', [$copied_document->id]),
        ]);
    }
}
