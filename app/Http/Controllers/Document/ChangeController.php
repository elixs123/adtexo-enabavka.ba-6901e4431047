<?php

namespace App\Http\Controllers\Document;

use App\Document;
use App\DocumentChange;
use App\DocumentProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\ChangeDocumentRequest;
use App\Support\Controller\DocumentProductHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ChangeController
 *
 * @package App\Http\Controllers\Document
 */
class ChangeController extends Controller
{
    use DocumentProductHelper;
    
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * @var \App\DocumentChange
     */
    private $documentChange;
    
    /**
     * @var \App\DocumentProduct
     */
    private $documentProduct;
    
    /**
     * ChangeController constructor.
     *
     * @param \App\Document $document
     * @param \App\DocumentProduct $documentProduct
     * @param \App\DocumentChange $documentChange
     */
    public function __construct(Document $document, DocumentProduct $documentProduct, DocumentChange $documentChange)
    {
        $this->document = $document;
        $this->documentChange = $documentChange;
        $this->documentProduct = $documentProduct;
    }
    
    /**
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $item = $this->document->getOne($id);
    
        if (is_null($item)) {
            abort(404, trans('document.errors.not_found', ['id' => $id]));
        }
    
        $product_id = request('product_id');
        $documentProduct = is_null($product_id) ? null : $this->documentProduct->getOneByProdcutIdAndDocumentId($product_id, $id);
        
        $changes = $item->rDocumentChanges();
        if (!is_null($product_id)) {
            $changes->where('product_id', $product_id);
        }
        
        return view('document.changes.index')->with([
            'title' => $item->full_name,
            'item' => $item,
            'product' => $documentProduct,
            'changes' => $changes->with('rChangedBy.rPerson')->latest()->get(),
        ]);
    }
    
    /**
     * @param \App\Http\Requests\Document\ChangeDocumentRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(ChangeDocumentRequest $request, $id)
    {
        $document = $this->document->getOne($id);
        
        if (!is_null($document)) {
            $quantities = $request->get('product', []);
            
            if (!empty($quantities)) {
                $this->documentProduct->includeIds = array_keys($quantities);
                $document_products = $this->documentProduct->getAll();
                
                $this->dbTransaction(function () use ($document, $quantities, $document_products) {
                    $now = now()->toDateTimeString();
                    
                    $document_changes = [];
                    foreach ($document_products as $document_product) {
                        if (isset($quantities[$document_product->id]) && ((int) $quantities[$document_product->id] != (int) $document_product->qty)) {
                            $change = [
                                'document_id' => $document_product->document_id,
                                'changed_by' => $this->getUserId(),
                                'product_id' => $document_product->product_id,
                                'type' => 'quantity',
                                'value' => $document_product->qty,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                            
                            if ((int) $quantities[$document_product->id] == 0) {
                                $document_product->delete();
                                // $this->documentProduct->remove($document_product->id);
    
                                $document->updateTotals();
    
                                $change['type'] = 'remove';
                            } else {
                                $this->updateDocumentProduct($document, $document_product, (int) $quantities[$document_product->id]);
                            }
    
                            $document_changes[] = $change;
                        }
                    }
                    
                    if (!empty($document_changes)) {
                        DB::table($this->documentChange->getTable())->insert($document_changes);
                    }
                    
                    if (!$document->delivery_cost_fixed) {
                        $delivery_cost = calcDeliveryCost($document->delivery_type, $document->rStock->country_id, ($document->useMpcPrice() ? getPriceWithoutVat($document->total_discounted, $document->tax_rate) : $document->subtotal_discounted));
                        $document->update([
                            'delivery_cost' => $delivery_cost,
                        ]);
                    }
                });
            }
            
            if (userIsWarehouse()) {
                $document->update([
                    'package_number' => $request->get('package_number'),
                    'weight' => $request->get('weight'),
                ]);
            }
        }
        
        if ($request->expectsJson()) {
            return $this->getSuccessJsonResponse([
                'notification' => [
                    'type' => 'success',
                    'message' => trans('document.notifications.changed'),
                ],
            ]);
        }
        
        return redirect()->back()->with('success_msg',trans('document.notifications.changed'));
    }
}
