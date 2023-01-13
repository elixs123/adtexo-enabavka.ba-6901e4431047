<?php

namespace App\Http\Controllers\Luceed;

use App\Client;
use App\Http\Controllers\Controller;
use App\Libraries\Api\LuceedWebService;

/**
 * Class ClientController
 *
 * @package App\Http\Controllers\Luceed
 */
class ClientController extends Controller
{
    /**
     * @var \App\Client
     */
    private $client;
    
    /**
     * ClientController constructor.
     *
     * @param \App\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * @param \App\Libraries\Api\LuceedWebService $apiRequest
     * @param integer $id
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(LuceedWebService $apiRequest, $id)
    {
        $client = $this->client->getOne($id);
        dump($client->toArray());
    
        return $apiRequest->storeClient($client, 'test', true);
    }
    
    /**
     * @param \App\Libraries\Api\LuceedWebService $apiRequest
     * @param string $code
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function code(LuceedWebService $apiRequest, $code)
    {
        return $apiRequest->getClientByCode($code, true);
    }
}
