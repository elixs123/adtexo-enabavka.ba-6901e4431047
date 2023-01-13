<?php

namespace App\Http\Controllers\Home;

use App\Document;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class DocumentController
 *
 * @package App\Http\Controllers\Home
 */
class DocumentController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function expressPost(Request $request)
    {
        $documents = $request->get('d', []);
        
        return view('homepage.form.express-post')->with([
            'form_title' => trans('skeleton.choose_express_post'),
            'status' => 'express_post',
            'documents' => $documents,
        ]);
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function takeover(Request $request)
    {
        $documents = $request->get('d', []);
    
        return view('homepage.form.express-post')->with([
            'form_title' => trans('skeleton.choose_takeover'),
            'status' => 'retrieved',
            'documents' => $documents,
        ]);
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function pdf(Request $request)
    {
        $document = new Document();
        $document->limit = null;
        $document->typeId = ['order'];
        // $document->statusId = 'shipped';
        $document->includeIds = $request->get('d', []);
    
        $documents = $document->relation(['rClient', 'rExpressPost'])->getAll();
    
        return view('homepage.modal.express-post')->with([
            'form_title' => 'PDF',
            'documents' => $documents,
        ]);
    }
}
