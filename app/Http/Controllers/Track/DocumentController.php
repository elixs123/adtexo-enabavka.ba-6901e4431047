<?php

namespace App\Http\Controllers\Track;

use App\Document;
use App\Http\Controllers\Controller;

/**
 * Class DocumentController
 *
 * @package App\Http\Controllers\Track
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
     * @param string $hash
     * @param int $id
     * @return array|false|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function show($hash, $id)
    {
        $document = $this->document->getOne($id);
        abort_if(is_null($document), 404);
        abort_unless($document->isPublicHashValid($hash), 404);
        // abort_if(is_null($document->rExpressPost), 404);
        
        $client = $document->rClient;
        abort_if(is_null($client), 404);
        
        $document->rExpressPost()->update([
            'viewed_at' => now(),
        ]);
        
        return view('track.document')->with([
            'document' => $document,
            'client' => $client,
        ]);
    }
}
