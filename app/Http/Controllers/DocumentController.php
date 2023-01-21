<?php

namespace App\Http\Controllers;

use App\Client;
use App\Document;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Http\Requests\Document\UpdateDocumentRequest;
use App\Support\Controller\ActionHelper;
use App\Support\Controller\DocumentHelper;
use App\User;
use Illuminate\Support\Facades\DB;
use App\The_OrderItem;
use App\The_Order;
use App\Product;
use App\Subject;
use Illuminate\Http\Request;

/**
 * Class DocumentController
 *
 * @package App\Http\Controllers
 */
class DocumentController extends Controller
{

    use DocumentHelper, ActionHelper;
    
    /**
     * @var \App\Document
     */
    private $document;

    /**
     * @var \App\Client
     */
    private $client;
    
    /**
     * @var \App\User
     */
    private $user;
    
    /**
     * DocumentController constructor.
     *
     * @param \App\Document $document
     * @param \App\Client $client
     * @param \App\User $user
     */
    public function __construct(Document $document, Client $client, User $user)
	{
        $this->document = $document;
        $this->client = $client;
        $this->user = $user;

        $this->middleware('auth');
        $this->middleware('emptystringstonull');
        $this->middleware('acl:view-document', ['only' => ['index']]);
        $this->middleware('acl:create-document', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-document', ['only' => ['edit', 'update']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Throwable
     */
    public function index()
    {
        $orders = The_Order::with('subject')->get();

        return view('document.index', ['orders' => $orders]);
    }

    public function delete(Request $request){
        $request->validate([
            'orderNumber' => 'required:max:40'
        ]);

        The_Order::where('orderNumber', $request->input('orderNumber'))->delete();
        The_OrderItem::where('orderNumber', $request->input('orderNumber'))->delete();

        return redirect()->back();
    }
    
    /**
     * @param \Illuminate\Database\Eloquent\Collection $items
     * @return \Illuminate\Http\Response
     */
    private function exportToPDF($items)
    {
        // return view('document.export_pdf')->with('items', $items);
        
        return \PDF::loadView('document.export_pdf', ['items' => $items])->download('documents.pdf');
    }
    
    /**
     * @param \Illuminate\Database\Eloquent\Collection $items
     * @return \Illuminate\Http\Response
     */
    private function exportToExcel($items)
    {
        // return view('document.export_xls')->with('items', $items);
        
        return \Excel::create('documents', function($excel) use ($items) {
            $excel->sheet('Sheet 1', function($sheet) use ($items) {
                $sheet->loadView('document.export_xls')->with('items', $items);
            });
        })->download('xls');
    }
    
    /**
     * @param \Illuminate\Database\Eloquent\Collection $items
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Throwable
     */
    private function exportToXml($items)
    {
        // return view('document.export_xml')->with('items', $items);
        
        $xml_data = view('document.export_xml')->with([
            'items' => $items,
        ])->render();
        
        $xml_path = storage_path('cron/documents_'.now()->format('Ymd_His').'.xml');
        
        file_put_contents($xml_path, $xml_data);
    
        return response()->download($xml_path)->deleteFileAfterSend(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $type_id = request('type_id', 'preorder');
        $client_id = userIsClient() ? $this->getUser()->client->id : request('client_id');
        $client_type = null;
        $clients = [];
        $discount1 = 0;
        $discount2 = 0;
        
        if (is_null($client_id)) {
            return $this->chooseClient();
        } else {
            $client = $this->client->getOne($client_id);
            
            if (is_null($client)) {
                return $this->chooseClient();
            }
        }
        
        if (($type_id == 'offer') || ($type_id == 'cash') || ($client->payment_type == 'advance_payment')) {
            $payment_type = 'advance_payment';
            $payment_period = '00_days_period';
            $payment_type_lock = $payment_period_lock = true;
        } else {
            $payment_type = 'wire_transfer_payment';
            $payment_period = '30_days_period';
            $payment_type_lock = $payment_period_lock = false;
        }
        
        $clients[$client->id] = $client->full_name;
        if ($client->type_id == 'private_client') {
            $payment_type = 'cash_payment';
            $payment_period = '00_days_period';
            $client_type = 'private_client';
            $payment_type_lock = $payment_period_lock = true;
            $discount1 = ($type_id == 'cash') ? config('client.global_discount_value') : 0;
        } else {
            if ($type_id != 'offer') {
                $payment_type = is_null($client->payment_type) ? 'wire_transfer_payment' : $client->payment_type;
                $payment_period = is_null($client->payment_period) ? '30_days_period' : $client->payment_period;
                $payment_type_lock = $payment_period_lock = ($client->payment_type == 'advance_payment');
            }
            $client_type = 'business_client';
            $discount1 = is_null($client->payment_discount) ? 0 : $client->payment_discount;
            $discount2 = is_null($client->discount_value1) ? 0 : $client->discount_value1;
        }
        
        $delivery_types = get_codebook_opts('delivery_types')->pluck('name', 'code')->toArray();
        if (userIsSalesman() || userIsClient()) {
            unset($delivery_types['free_delivery']);
            
            // if (userIsSalesman()) {
            //     unset($delivery_types['personal_takeover']);
            // }
        }
        
        return view('document.form')
            ->with('item', $this->document)
            ->with('method', 'post')
            ->with('form_url', route('document.store'))
            ->with('form_title', trans('document.actions.create.'.$type_id))
            ->with('callback', request('callback', 'documentRedirect'))
            ->with('type_id', $type_id)
            ->with('payment_type', $payment_type)
            ->with('payment_type_lock', $payment_type_lock)
            ->with('payment_period', $payment_period)
            ->with('payment_period_lock', $payment_period_lock)
            ->with('client', $client)
            ->with('client_type', $client_type)
            ->with('clients', $clients)
            ->with('discount1', $discount1)
            ->with('discount2', $discount2)
            ->with('delivery_types', $delivery_types)
            ->with('stock', $client->rStock);
    }
    
    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    private function chooseClient()
    {
        $type_id = request('type_id', 'preorder');
        $type = get_codebook_opts('document_type')->where('code', $type_id)->first()->name;
        
        if ($type_id == 'cash') {
            $client_type = 'private_client';
        } else if (in_array($type_id, ['return', 'order'])) {
            $client_type = null;
        } else {
            $client_type = 'business_client';
        }
        
        return view('document.choose_client')
            ->with('item', $this->document)
            ->with('method', 'get')
            ->with('form_url', route('document.create'))
            ->with('form_title', $type.': '.trans('document.actions.choose_client'))
            ->with('type_id', $type_id)
            ->with('client_type', $client_type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Document\StoreDocumentRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreDocumentRequest $request)
    {
        $item = $request->except(['_token', 'express_post_type']);
        
        $client = $this->client->getOne($item['client_id']);
        $payment_discount = convert2float($request->get('payment_discount', $client->payment_discount));
        $discount_value1 = convert2float($item['discount_value1']);
        if ($client->type_id == 'private_client') {
            $payment_discount = $payment_discount + $discount_value1;
            $discount_value1 = 0;
        }
    
        // $item['discount_value1'] = convert2float($item['discount_value1']);
        $item['delivery_cost'] = $this->getDeliveryCost($item['delivery_type'], convert2float($item['delivery_cost']));
        $item['delivery_cost_fixed'] = $item['delivery_cost'] > 0;
        $item['created_by'] = $this->getUserId();
        $item['stock_id'] = $client->stock_id;
        $item['payment_discount'] = $payment_discount;
        $item['discount_value1'] = $discount_value1;
        $item['buyer_data'] = $client->toArray();
        $item['currency'] = $client->rStock->currency;
        $item['tax_rate'] = $client->rStock->tax_rate;
        
        $express_post_type = $request->get('express_post_type');
        
        $document = $this->dbTransaction(function () use ($item, $express_post_type) {
            $document = $this->document->add($item);
            
            $this->getUser()->rScopedDocument()->attach($document->id);
    
            if ($document->isOrder() && !is_null($express_post_type) && !is_null($document->stock_id)) {
                $document->rExpressPost()->create([
                    'stock_id' => $document->stock_id,
                    'express_post_type' => $express_post_type,
                    'status' => 'express_post',
                ]);
            }
            
            return $document;
        });
		      
        return $this->getStoreJsonResponse($document, 'document._row', trans('document.notifications.created'), [
            'redirect' => $request->get('back', route('shop.index')),
        ]);
    }
    
    /**
     * @param string $deliveryType
     * @param float $deliveryCost
     * @return float|int
     */
    private function getDeliveryCost($deliveryType, $deliveryCost)
    {
        if (userIsSalesAgent()) {
            if (!is_null($client = auth()->user()->rClients->first())) {
                return calcDeliveryCost($deliveryType, $client->country_id, 1, 0);
            }
        }
        
        return $deliveryCost;
    }
    
    /**
     * Show.
     *
     * @param int $id
     * @return \Illuminate\View\View|mixed
     */
    public function show($id)
    {
        if (userIsSalesman()) {
            $this->document->createdBy = $this->getUserId();
        }
        if (userIsClient()) {
            $this->document->clientId = $this->getUser()->client->id;
        }
        $this->document->relation(['rStatus', 'rType', 'rClient', 'rClient.rHeadquarter']);
        $item = $this->document->getOne($id);
        
        if (is_null($item)) {
            abort(404, trans('document.errors.not_found', ['id' => $id]));
        }
    
        $products = $item->rDocumentProduct()->with(['rDocument', 'rUnit'])->get();
    
        $gratis_products = collect([]);
        if ($item->isAction()) {
            if ($item->rAction->isGratis()) {
                $gratis_products = $item->rDocumentGratisProducts()->with(['rDocument', 'rUnit'])->get();
            }
        }
        
        $export_type = request('export_type');
    
        if (request('export') == 'xls') {
            if ($export_type == 'gratis') {
                $export_products = $gratis_products;
            } else {
                $export_products = $products->merge($gratis_products);
            }
            
            // return view('document.export_xls_document')->with([
            //     'items' => $export_products,
            // ]);
            
            return \Excel::create($item->full_name, function($excel) use ($item, $export_products) {
                $excel->sheet('Ulistavanje artikla', function($sheet) use ($item, $export_products) {
                    $sheet->loadView('document.export_xls_document')
                        ->with('items', $export_products);
                });
            })->download('xls');
        }
        
        $view = '_show';
        if ($item->created_at->toDateTimeString() <= config('client.mpc_start_timestamp')) {
            $view = '__show';
        } else if ($item->id >= config('client.pantheon_document_id')) {
            $view = 'show';
        }
        
        // $view = ($item->created_at->toDateTimeString() <= config('client.mpc_start_timestamp')) ? '__show' : 'show';
    
        if (request('export') == 'pdf') {
            return \PDF::loadView("document.{$view}", [
                'document' => $item,
                'products' => $products,
                'changes' => [],
                'gratis_product' => $gratis_products,
                'export_to_pdf' => true,
            ])->download('products.pdf');
        }
        
        if (($item->type_id == 'order') && in_array($item->status, ['in_process', 'in_warehouse', 'warehouse_preparing']) && (userIsEditor() || userIsWarehouse())) {
            $promo_products = [];
            foreach ($products as $product) {
                if ($product->is_promo_product) {
                    foreach ($product->promo_children as $promo_child) {
                        $promo_child['qty'] = (int) $promo_child['qty'];
                        
                        $promo_products[] = (object) $promo_child;
                    }
                } else {
                    $promo_products[] = $product;
                }
            }
            
            $products = collect($promo_products);
        }
    
        return view("document.{$view}")
            ->with('document', $item)
            ->with('products', $products)
            ->with('changes', $item->rDocumentChanges()->with(['rChangedBy.rPerson'])->get()->keyBy('product_id'))
            ->with('gratis_products', $gratis_products)
            ->with('export_to_pdf', false);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->document->getOne($id);
    
        $clients = [];
        if (!is_null($item->rClient)) {
            $clients[$item->client_id] = $item->rClient->full_name;
        }

        return view('document.form')
            ->with('method', 'put')
            ->with('form_url', route('document.update', [$id]))
            ->with('form_title', trans('document.actions.edit'))
            ->with('item', $item)
            ->with('client', $this->client)
            ->with('clients', $clients);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Document\UpdateDocumentRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateDocumentRequest $request, $id)
    {
        $item = request()->except(['token']);

        $document = $this->document->edit($id, $item);

        return $this->getUpdateJsonResponse($this->document->getOne($document->id), 'document._row', trans('document.notifications.updated'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy($id)
    {
        $document = $this->document->getOne($id);

        if (!is_null($document)) {
            if (!is_null($document->rParent)) {
                $document->rParent->update([
                    'parent_id' => null,
                ]);
            }
            
            if (!is_null($document->rChild)) {
                $document->rChild->update([
                    'parent_id' => null,
                ]);
            }
            
            DB::table('document_scope')->where('document_id', $id)->delete();
            
            $document->rDocumentProductAll()->delete();
            
            $document->rDocumentChanges()->delete();
            
            $document->rExpressPost()->delete();
            
            $document->rTakeover()->delete();
            
            $document->rExpressPostEvents()->delete();
            
            $this->document->remove($document->id);

            return $this->getDestroyJsonResponse($document, null, trans('document.notifications.deleted'));
        }

        return $this->getDestroyJsonResponse($document, null, 'Dokument nije obrisan.');
    }
}
