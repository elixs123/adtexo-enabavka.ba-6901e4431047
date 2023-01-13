<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Support\Scoped\ScopedDocumentFacade;

/**
 * Class CloseController
 *
 * @package App\Http\Controllers\Document
 */
class CloseController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function close()
    {
        ScopedDocumentFacade::close();
        
        return $this->getSuccessJsonResponse([
            'notification' => [
                'type' => 'success',
                'message' => trans('document.notifications.closed'),
            ],
        ]);
    }
}
