<?php

namespace App\Http\Controllers\Document;

use App\Document;
use App\Http\Controllers\Controller;

/**
 * Class TrackController
 *
 * @package App\Http\Controllers\Document
 */
class TrackController extends Controller
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
     * @param $id
     * @return array|false|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function show($id)
    {
        $this->document->typeId = 'order';
        $document = $this->document->getOne($id);
        abort_if(is_null($document), 404);
        
        $client = $document->rClient;
        abort_if(is_null($client), 404);
        
        return view('document.track.show')->with([
            'document' => $document,
            'client' => $client,
        ]);
    }
}
