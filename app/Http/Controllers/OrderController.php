<?php

namespace App\Http\Controllers;

use App\Document;
use App\Libraries\Api\PantheonApi;
use App\Libraries\PantheonHelper;

/**
 * Class OrderController
 *
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    public function test()
    {
        $document = Document::with(['rDocumentProduct'])->findOrFail(24471);
        // return PantheonHelper::mapDocumentForRequest($document);
    
        $pantheon_api = new PantheonApi();
        $pantheon_api->responseAssoc = true;
    
        $response = $pantheon_api->insertOrder(PantheonHelper::mapDocumentForRequest($document));
    
        if ($pantheon_api->hasErrors()) {
            return "Document #{$document->id} - Pantheon response: ".(json_encode($pantheon_api->getErrors()));
        } else if (is_null($response)) {
            return "Document #{$document->id} - Pantheon response is NULL ";
        }
        dump($response);
    
        $order = PantheonHelper::createOrderFromResponse($response, $document->id);
    
        PantheonHelper::syncDocumentWithOrder($document, $order->id);
        
        return $order->toArray();
    }
}
