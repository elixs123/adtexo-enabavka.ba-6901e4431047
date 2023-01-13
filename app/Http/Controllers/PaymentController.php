<?php

namespace App\Http\Controllers;

use App\FileHelper;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Payment;
use App\Support\Controller\PaymentHelper;
use Illuminate\Support\Facades\DB;

/**
 * Class PaymentController
 *
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    use FileHelper, PaymentHelper;
    
    /**
     * @var \App\Payment
     */
    private $payment;
    
    /**
     * PaymentController constructor.
     *
     * @param \App\Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    
        $this->middleware('auth');
        $this->middleware('acl:view-payment', ['only' => ['index']]);
        $this->middleware('acl:create-payment', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-payment', ['only' => ['edit', 'update']]);
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $this->payment->statusId = request('status');
        $this->payment->typeVal = request('type');
        $this->payment->serviceVal = request('service');
        $this->payment->paginate = true;
        $this->payment->limit = 10;
        $items = $this->payment->relation(['rUploadedBy', 'rConfirmedBy', 'rStatus'])->getAll();
        
        return view('payment.index')->with([
            'items' => $items,
        ]);
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create()
    {
        return view('payment.form')->with([
            'item' => $this->payment,
            'method' => 'post',
            'form_url' => route('payment.store'),
            'form_title' => trans('payment.actions.create'),
        ]);
    }
    
    /**
     * @param \App\Http\Requests\Payment\StorePaymentRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StorePaymentRequest $request)
    {
        $input = $request->only([
            'type',
            'service',
            'status',
        ]);
        $input['uploaded_at'] = now();
        $input['uploaded_by'] = auth()->id();
        $input['config'] = config('payment.services.'.$input['service']);
        $input['file'] = $this->uploadFile('file', 'payment', config('file.payment.path'), $request, true);
    
        $payment = $this->dbTransaction(function () use ($input) {
            $payment = $this->payment->add($input);
        
            $this->syncPaymentItems($payment);
            
            return $payment;
        });
    
        return $this->getStoreJsonResponse($payment->fresh(['rStatus']), 'payment._row', trans('payment.notifications.created'));
    }
    
    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show($id)
    {
        $item = $this->payment->getOne($id);
        abort_if(is_null($item), 404);
        
        $items = $item->rPaymentItems;
        
        $documents = DB::table('documents')->whereIn('id', $items->pluck('document_id')->toArray())->get(['id', 'total_discounted', 'delivery_cost'])->map(function($document) use ($item) {
            if ($item->type == 'express_post') {
                $total = round($document->total_discounted + $document->delivery_cost, 2);
            } else {
                $total = round($document->total_discounted, 2);
            }
            
            return [
                'id' => $document->id,
                'total' => $total,
            ];
        })->pluck('total', 'id')->toArray();
        
        return view('payment.show')->with([
            'payment' => $item,
            'items' => $items,
            'documents' => $documents,
        ]);
    }
    
    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->payment->getOne($id);
    
        return view('payment.form')->with([
            'item' => $item,
            'method' => 'put',
            'form_url' => route('payment.update', [$id]),
            'form_title' => trans('payment.actions.edit'),
        ]);
    }
    
    /**
     * @param \App\Http\Requests\Payment\UpdatePaymentRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdatePaymentRequest $request, $id)
    {
        $input = [];
    
        if ($request->hasFile('file')) {
            $input['uploaded_at'] = now();
            $input['uploaded_by'] = auth()->id();
            $input['file'] = $this->uploadFile('file', 'payment', config('file.payment.path'), $request, true);
        }
        
        $sync_items = $request->hasFile('file');
    
        $payment = $this->dbTransaction(function () use ($id, $input, $sync_items) {
            $payment = $this->payment->edit($id, $input);
            
            if ($sync_items) {
                $this->syncPaymentItems($payment);
            }
            
            return $payment;
        });
    
        return $this->getUpdateJsonResponse($payment->fresh(['rStatus']), 'payment._row', trans('payment.notifications.updated'));
    }
    
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm($id)
    {
        $item = $this->payment->getOne($id);
        abort_if(is_null($item), 404);
        abort_unless($item->status == 'not_confirmed', 404);
        
        $this->dbTransaction(function () use ($item) {
            $item->update([
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
                'status' => 'confirmed',
            ]);
            
            DB::table('documents')->whereIn('id', $item->rPaymentItems->pluck('document_id')->toArray())->update([
                'is_payed' => 1,
                'payed_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ]);
        });
        
        return redirect()->back()->with('success_msg', trans('payment.notifications.confirmed'));
    }
}
