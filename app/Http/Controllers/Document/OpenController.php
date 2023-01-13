<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Support\Scoped\ScopedDocumentFacade;
use App\User;
use Illuminate\Support\Facades\DB;

/**
 * Class OpenController
 *
 * @package App\Http\Controllers\Document
 */
class OpenController extends Controller
{
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function open($id)
    {
        $document_scope = DB::table('document_scope')->where('document_id', $id)->get()->first();
        
        if (!is_null($document_scope)) {
            $user = User::find($document_scope->user_id);
            
            return $this->getErrorJsonResponse(trans('document.notifications.opened_other', ['person' => is_null($user) ? 'John Doe' : (is_null($user->rPerson) ? $user->email : $user->rPerson->name)]), 422, []);
        }
        
        ScopedDocumentFacade::open($id);
        
        return $this->getSuccessJsonResponse([
            'notification' => [
                'type' => 'success',
                'message' => trans('document.notifications.opened'),
            ],
            'redirect' => route('document.show', [$id]), // ScopedDocumentFacade::totalItems() ? route('document.show', [$id]) : route('shop.index'),
        ]);
    }
}
