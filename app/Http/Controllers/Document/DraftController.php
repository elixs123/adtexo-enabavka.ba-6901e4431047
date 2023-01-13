<?php

namespace App\Http\Controllers\Document;

use App\Document;
use App\DocumentProduct;
use App\Http\Controllers\Controller;
use App\Support\Scoped\ScopedDocumentFacade;
use App\Support\Controller\DocumentHelper;

/**
 * Class DraftController
 *
 * @package App\Http\Controllers\Document
 */
class DraftController extends Controller
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
     * DraftController constructor.
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
     * Index.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->document->statusId = 'draft';
        $this->document->scopedOnly = false;
        if (userIsSalesman()) {
            $this->document->createdBy = $this->getUserId();
        }
        $this->document->limit = null;
        if (userIsClient()) {
            $this->document->clientId = $this->getUser()->client->id;
        } else {
            $this->document->clientId = request('client_id');
        }
        $this->document->typeId = request('type_id');
        $this->document->dateOfOrder = request('date_of_order');
        $this->document->startDate = now()->subDays(30)->toDateString();
        $this->document->endDate = now()->addDays(15)->toDateString();
        $items = $this->document->relation(['rClient', 'rType', 'rCreatedBy'], true)->getAll();
        
        return view('shop.document.choose')
            ->with('items', $items)
            ->with('method', 'post')
            ->with('form_url', route('document.draft.choose'))
            ->with('form_title', trans('document.actions.choose'))
            ->with('callback', request('callback', 'documentReload'));
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function choose()
    {
        ScopedDocumentFacade::open($id = (int)request('document_id', 0));
    
        return $this->getUpdateJsonResponse(ScopedDocumentFacade::getDocument(), null, trans('document.notifications.opened'), [
            'redirect' => route('document.show', ['id' => $id]),
        ]);
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete()
    {
        if (!ScopedDocumentFacade::exist()) {
            return $this->getErrorJsonResponse(trans('document.errors.scoped.not_found'), 404);
        }
        
        if (!ScopedDocumentFacade::totalItems()) {
            return $this->getErrorJsonResponse(trans('document.errors.scoped.no_items'), 422);
        }
        
        $document = ScopedDocumentFacade::getDocument();
        
        $completed = $this->completeDocument($document);
        
        return $this->getSuccessJsonResponse([
            'notification' => ($document->isOrder() || $document->isCash()) ? [] : [
                'type' => 'success',
                'message' => trans('document.notifications.completed'),
            ],
            'redirect' => ($document->isOrder() || $document->isCash()) ? route('cart.index') : route('document.show', ['id' => $completed->id]),
        ]);
    }
}
