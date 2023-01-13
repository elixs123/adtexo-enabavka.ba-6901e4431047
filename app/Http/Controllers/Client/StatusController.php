<?php

namespace App\Http\Controllers\Client;

use App\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class StatusController
 *
 * @package App\Http\Controllers\Client
 */
class StatusController extends Controller
{
    /**
     * @var \App\Client
     */
    private $client;
    
    /**
     * StatusController constructor.
     *
     * @param \App\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * Change status.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function change(Request $request)
    {
        $status = $request->get('s');
        
        $this->client->statusId = 'pending';
        $this->client->includeIds = $request->get('c', []);
        $clients = $this->client->getAll();
        
        $this->dbTransaction(function () use ($clients, $status) {
            foreach ($clients as $client) {
                $client->update([
                    'status' => $status,
                ]);
            }
        });
        
        return $this->getSuccessJsonResponse([
            'status' => $status,
            'items' => $clients->pluck('uid')->toArray(),
            'notification' => [
                'type' => 'success',
                'message' => trans('client.notifications.status', ['status' => get_codebook_opts('status')->where('code', $status)->first()->name]),
            ],
        ]);
    }
}
