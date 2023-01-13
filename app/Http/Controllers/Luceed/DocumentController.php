<?php

namespace App\Http\Controllers\Luceed;

use App\Document;
use App\Http\Controllers\Controller;
use App\Libraries\Api\LuceedWebService;

/**
 * Class DocumentController
 *
 * @package App\Http\Controllers\Luceed
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
     * @param \App\Libraries\Api\LuceedWebService $apiRequest
     * @param integer $id
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(LuceedWebService $apiRequest, $id)
    {
        $document = $this->document->relation(['rClient', 'rDocumentProduct'], true)->getOne($id);
    
        return $apiRequest->storeDocument($document, 'test', true);
    }
    
    /**
     * @param \App\Libraries\Api\LuceedWebService $apiRequest
     * @param int $id
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function id(LuceedWebService $apiRequest, $id)
    {
        $document = $this->document->relation([], true)->getOne($id);
    
        return $apiRequest->getDocumentById($document, true);
    }
}
