<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Http\Requests\Client\StoreApiClientRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\UpdateApiClientRequest;
use App\Http\Resources\Client\ClientCollection;
use App\Http\Resources\Client\ClientResource;
use App\Person;

/**
 * Class ClientController
 *
 * @package App\Http\Controllers\Api
 */
class ClientController extends Controller
{
    /**
     * @var \App\Client
     */
    private $client;
    
    /**
     * DocumentController constructor.
     *
     * @param \App\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\Client\ClientCollection
     * @throws \Throwable
     */
    public function index()
    {
        $statusId = request()->get('status');
        $typeId = request('type_id');
        $parentId = request('parent_id');
        $keywords = request()->get('keywords');
        $countryId = request()->get('country_id');
        $limit = request('limit', 100);

        $this->client->countryId = $countryId;
        $this->client->typeId = $typeId;
        $this->client->parentId = $parentId;
		$this->client->statusId = $statusId;
        $this->client->keywords = $keywords;
        $this->client->limit = $limit;
        $this->client->paginate = true;
        $this->client->relation(['rStatus', 'rType', 'rPaymentPeriod', 'rCategory', 'rStock', 'rCountry', 'rPaymentType'], true);
        
        $items = $this->client->getAll();
		
		return new ClientCollection($items);
    }

    /**
     * @param StoreApiClientRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreApiClientRequest $request)
    {
        $input = $request->only($this->requestData());
        $input['is_location'] = $request->get('is_location', 0);
    
        $client = $this->client->add($input);
        
        $this->syncClientSalesmanPerson($client, $input['salesman_person_id'] ?? null);

        return new ClientResource($client);
    }
    
    /**
     * Request data.
     *
     * @return array
     */
    private function requestData()
    {
        return [
            'parent_id',
            'type_id',
            'jib',
            'pib',
            'code',
            
            'name',
            'address',
            'city',
            'postal_code',
            'country_id',
            'latitude',
            'longitude',
            'phone',
            
            'status',
            'note',
    
            'payment_therms',
            'payment_period',
            'payment_type',
            'payment_discount',
            'discount_value1',
            'discount_value2',
    
            'stock_id',
            'lang_id',
            
            'is_location',
            'location_code',
            'location_name',
            
            'client_person_id',
            'responsible_person_id',
            'payment_person_id',
            'salesman_person_id',
            'supervisor_person_id',
        ];
    }
    
    /**
     * @param \App\Client|mixed $client
     * @param integer|null $salesmanPersonId
     * @return void
     */
    private function syncClientSalesmanPerson($client, $salesmanPersonId)
    {
        if ($client->is_location && not_null($salesmanPersonId)) {
            $person = Person::query()
                ->where('type_id', 'salesman_person')
                ->find($salesmanPersonId);
            
            if (not_null($person)) {
                $client->rSalesmanPersons()->sync([
                    $salesmanPersonId => [
                        'week' => 0,
                        'day' => null,
                        'rank' => 9999,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
        }
    }
    
    /**
     * @param int $id
     * @return \App\Http\Resources\Client\ClientResource
     */
    public function show($id)
    {
        $item = $this->client->getOne($id);
        
        return new ClientResource($item);
    }
    
    /**
     * @param \App\Http\Requests\Client\UpdateApiClientRequest $request
     * @param int $id
     * @return \App\Http\Resources\Client\ClientResource
     */
    public function update(UpdateApiClientRequest $request, $id)
    {
        $input = $request->only($this->requestData());
        $input['is_location'] = $request->get('is_location', 0);
    
        $client = $this->client->getOne($id);
    
        $was_location = $client->is_headquarter && $client->is_location && !$request->get('is_location', 0);
    
        $client = $this->client->edit($id, $input);
    
        $this->syncClientSalesmanPerson($client, $input['salesman_person_id'] ?? null);
    
        if ($was_location) {
            $client->update([
                'location_code' => null,
                'location_name' => null,
                'location_type_id' => null,
                'category_id' => null,
            ]);
        }
    
        return new ClientResource($client);
    }
}
