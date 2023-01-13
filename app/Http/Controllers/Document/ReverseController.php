<?php

namespace App\Http\Controllers\Document;

use App\Document;
use App\Http\Controllers\Controller;

/**
 * Class ReverseController
 *
 * @package App\Http\Controllers\Document
 */
class ReverseController extends Controller
{
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * ReverseController constructor.
     *
     * @param \App\Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
    
    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reverse($id)
    {
        $document = $this->document->getOne($id);
        if (is_null($document)) {
            return $this->getErrorJsonResponse(trans('document.errors.not_found', ['id' => $id]), 404);
        }
        
        if (!$document->canBeReversed()) {
            return $this->getErrorJsonResponse(trans('document.errors.not_found', ['id' => $id]), 404);
        }
    
        $reverse = $this->dbTransaction(function () use ($id, $document) {
            $reverse = $document->replicate();
            $reverse->parent_id = $id;
            $reverse->created_by = auth()->id();
            $reverse->type_id = 'reversal';
            $reverse->date_of_order = now();
            $reverse->status = 'in_process';
            $reverse->save();
            
            $products = $document->rDocumentProductAll->toArray();
            $reverse->rDocumentProductAll()->createMany($products);
            
            $document->update([
                'status' => 'reversed',
            ]);
            
            return $reverse;
        });
        
        return $this->getSuccessJsonResponse([
           'url' => route('document.status.change', ['s' => 'completed', 't' => 'reversal', 'd[]' => $reverse->id]),
        ]);
    }
}
