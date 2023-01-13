<?php

namespace App\Http\Controllers\Document;

use App\Document;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class ExpressPostController
 *
 * @package App\Http\Controllers\Document
 */
class ExpressPostController extends Controller
{
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * ExpressPostController constructor.
     *
     * @param \App\Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function pdf(Request $request, $id)
    {
        $document = $this->document->getOne($id);
    
        if (!is_null($document)) {
            $express_post = $document->rExpressPost;
            
            if (!is_null($express_post)) {
                $type = $request->get('type');
                
                if ($type == 'label') {
                    return view('document.express-post-pdf', [
                        'title' => $document->full_name,
                        'base64' => $express_post->pdf_label,
                    ]);
                } else if ($type == 'pickup') {
                    return view('document.express-post-pdf', [
                        'title' => $document->full_name,
                        'base64' => $express_post->pdf_pickup,
                    ]);
                }
            }
        }
        
        abort(404, trans('document.errors.not_found', ['id' => $id]));
    }
}
