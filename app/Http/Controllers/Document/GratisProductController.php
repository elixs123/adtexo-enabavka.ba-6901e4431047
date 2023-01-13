<?php

namespace App\Http\Controllers\Document;

use App\Document;
use App\DocumentProduct;
use App\Http\Controllers\Controller;
use App\ProductTranslation;
use App\Stock;
use App\Support\Controller\ActionHelper;
use Illuminate\Http\Request;

/**
 * Class GratisProductController
 *
 * @package App\Http\Controllers\Document
 */
class GratisProductController extends Controller
{
    use ActionHelper;
    
    /**
     * @var \App\Document
     */
    private $document;
    /**
     * @var \App\DocumentProduct
     */
    private $documentProduct;
    /**
     * @var \App\Stock
     */
    private $stock;
    
    /**
     * GratisProductController constructor.
     *
     * @param \App\Document $document
     * @param \App\DocumentProduct $documentProduct
     * @param \App\Stock $stock
     */
    public function __construct(Document $document, DocumentProduct $documentProduct, Stock $stock)
    {
        $this->document = $document;
        $this->documentProduct = $documentProduct;
        $this->stock = $stock;
    
        $this->middleware('auth');
        $this->middleware('acl:view-document', ['only' => ['index', 'show']]);
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $export = request()->get('export', false);
        $startDate = request('start_date') ?: now()->startOfMonth()->toDateString();
        $endDate = request('end_date') ?: now()->endOfMonth()->toDateString();
        
        $processed = false;
        if (request()->has('processed')) {
            if (request('processed') == '') {
                $processed = null;
            } else {
                $processed = (bool)(((int)request('processed', 0)));
            }
        }
        
        $this->documentProduct->paginate = true;
        $this->documentProduct->limit = 15;
        $this->documentProduct->typeId = 'gratis';
        $this->documentProduct->gratisProcessed = $processed;
        $this->documentProduct->stockId = request('stock');
        if ($export == 'xls') {
            $p = request('p');
            if ($p == 'all') {
                $this->documentProduct->paginate = false;
                $this->documentProduct->limit = null;
            } else {
                $this->documentProduct->includeIds = explode('-', request('p'));
            }
        }
        $this->documentProduct->startDate = $startDate;
        $this->documentProduct->endDate = $endDate;
        $this->documentProduct->statusId = ['invoiced', 'express_post', 'shipped', 'express_post_in_process', 'delivered', 'retrieved'];
        $items = $this->documentProduct->relation(['rDocument'])->getAll();
    
        if ($export == 'xls') {
            return $this->exportToExcel($items);
        }
        
        $stocks = $this->stock->getAll()->pluck('name', 'id')->prepend('All', '')->toArray();
        
        return view('document.gratis.index')->with([
            'items' => $items,
            'stocks' => $stocks,
            'processed' => is_null($processed) ? null : ($processed ? '1' : '0'),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }
    
    /**
     * @param \Illuminate\Database\Eloquent\Collection $items
     * @return \Illuminate\Http\Response
     */
    private function exportToExcel($items)
    {
        // return view('document.gratis.export_xls')->with('items', $items);
        
        return \Excel::create('gratis_lager', function($excel) use ($items) {
            $excel->sheet('Sheet 1', function($sheet) use ($items) {
                $sheet->loadView('document.gratis.export_xls')->with('items', $items);
            });
        })->download('xls');
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function process(Request $request)
    {
        $products = $request->get('p', []);
    
        $this->documentProduct->limit = null;
        $this->documentProduct->typeId = 'gratis';
        $this->documentProduct->gratisProcessed = false;
        $this->documentProduct->includeIds = $products;
        $items = $this->documentProduct->getAll();
        
        $this->dbTransaction(function () use ($items) {
            foreach ($items as $item) {
                $item->update([
                    'processed_at' => now()
                ]);
            }
        });
        
        return $this->getSuccessJsonResponse([
            'redirect' => route('document.gratis', ['export' => 'xls', 'processed' => '1', 'p' => implode('-', $products)]),
        ]);
    }
    
    /**
     * @param integer $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show($id)
    {
        if (userIsSalesman()) {
            $this->document->createdBy = $this->getUserId();
        }
        if (userIsClient()) {
            $this->document->clientId = $this->getUser()->client->id;
        }
        $this->document->statusId = ['for_invoicing', 'invoiced'];
        $this->document->relation(['rStatus', 'rType', 'rClient', 'rClient.rHeadquarter']);
        $item = $this->document->getOne($id);
    
        if (is_null($item)) {
            abort(404, trans('document.errors.not_found', ['id' => $id]));
        }
        
        if (!$item->isAction()) {
            abort(404, trans('document.errors.not_found', ['id' => $id]));
        }
        
        $action = $item->rAction;
    
        $gratis_products = $item->rDocumentGratisProducts()->with(['rDocument', 'rUnit'])->get();
        
        return view('document.gratis.show')->with([
            'document' => $item,
            'action' => $action,
            'gratis_products' => $gratis_products,
        ]);
    }
}
