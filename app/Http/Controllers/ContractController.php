<?php

namespace App\Http\Controllers;

use App\Client;
use App\Contract;
use App\ContractProduct;
use App\Http\Requests\Contract\StoreContractRequest;
use App\Http\Requests\Contract\UpdateContractRequest;

/**
 * Class ContractController
 *
 * @package App\Http\Controllers
 */
class ContractController extends Controller
{
    /**
     * @var \App\Contract
     */
    private $contract;
    
    /**
     * @var \App\ContractProduct
     */
    private $contractProduct;
    /**
     * @var \App\Client
     */
    private $client;
    
    /**
     * ContractController constructor.
     *
     * @param \App\Contract $contract
     * @param \App\ContractProduct $contractProduct
     * @param \App\Client $client
     */
    public function __construct(Contract $contract, ContractProduct $contractProduct, Client $client)
    {
        $this->contract = $contract;
        $this->contractProduct = $contractProduct;
        $this->client = $client;
    
        $this->middleware('auth');
        $this->middleware('acl:view-contract', ['only' => ['index']]);
        $this->middleware('acl:create-contract', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-contract', ['only' => ['edit', 'update']]);
    }
    
    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $this->contract->paginate = true;
        $this->contract->limit = 10;
        $items = $this->contract->relation(['rClient', 'rStatus'])->getAll();
    
        return view('contract.index')
            ->with('items', $items);
    }
    
    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function create()
    {
        $clients = [];
        
        return view('contract.form')
            ->with('item', $this->contract)
            ->with('method', 'post')
            ->with('form_url', route('contract.store'))
            ->with('form_title', trans('contract.actions.create'))
            ->with('clients', $clients);
    }
    
    /**
     * @param \App\Http\Requests\Contract\StoreContractRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreContractRequest $request)
    {
        $input = $request->only([
            'client_id',
            'note',
            'status',
        ]);
        
        $contract = $this->contract->add($input);
    
        return $this->getStoreJsonResponse($contract->fresh(['rClient', 'rStatus']), 'contract._row', trans('contract.notifications.created'));
    }
    
    /**
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function edit($id)
    {
        $item = $this->contract->getOne($id);
    
        $clients = [
            $item->client_id => $item->rClient->name,
        ];
    
        return view('contract.form')
            ->with('method', 'put')
            ->with('form_url', route('contract.update', [$id]))
            ->with('form_title', trans('contract.actions.edit'))
            ->with('item', $item)
            ->with('clients', $clients);
    }
    
    /**
     * @param \App\Http\Requests\Contract\UpdateContractRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateContractRequest $request, $id)
    {
        $input = $request->only([
            'note',
            'status',
        ]);
    
        $contract = $this->contract->edit($id, $input);
    
        return $this->getUpdateJsonResponse($contract->fresh(['rClient', 'rStatus']), 'contract._row', trans('contract.notifications.updated'));
    }
}
