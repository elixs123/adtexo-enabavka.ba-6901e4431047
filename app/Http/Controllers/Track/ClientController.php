<?php

namespace App\Http\Controllers\Track;

use App\Client;
use App\Document;
use App\Http\Controllers\Controller;

/**
 * Class ClientController
 *
 * @package App\Http\Controllers\Track
 */
class ClientController extends Controller
{
    /**
     * @var \App\Client
     */
    private $client;
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * ClientController constructor.
     *
     * @param \App\Client $client
     * @param \App\Document $document
     */
    public function __construct(Client $client, Document $document)
    {
        $this->client = $client;
        $this->document = $document;
    }
    
    /**
     * @param string $hash
     * @param int $id
     * @return array|false|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function show($hash, $id)
    {
        $client = $this->client->getOne($id);
        abort_if(is_null($client), 404);
        abort_unless($client->isPublicHashValid($hash), 404);
    
        $tabs = [
            'shipped' => get_codebook_opts('document_status')->where('code', 'shipped')->first()->name,
            'express_post_in_process' => get_codebook_opts('document_status')->where('code', 'express_post_in_process')->first()->name,
            'delivered' => get_codebook_opts('document_status')->where('code', 'delivered')->first()->name,
        ];
        
        $status = request('status', 'shipped');
        abort_unless(isset($tabs[$status]), 404);
        
        $this->document->typeId = 'order';
        $this->document->statusId = $status;
        $this->document->clientId = $id;
        $this->document->limit = 30;
        $documents = $this->document->relation(['rStatus', 'rPaymentType'])->getAll();
        
        return view('track.client')->with([
            'client' => $client,
            'documents' => $documents,
            'tabs' => $tabs,
            'status' => $status,
            'client_public_url' => $client->public_url,
        ]);
    }
}
