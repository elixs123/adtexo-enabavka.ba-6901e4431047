<?php

namespace App\Support\Controller\Dashboard;

use App\Client;
use App\Document;

/**
 * Trait DashboardEditorHelper
 *
 * @package App\Support\Controller\Dashboard
 */
trait DashboardEditorHelper
{
    /**
     * @param array $data
     * @return void
     */
    private function getEditorData(&$data)
    {
        if (userIsEditor()) {
            $data['dates_data'] = $this->getDatesData();
            
            $data['main_tabs'] = [
                'documents' => 'Dokumenti',
                'return' => 'Povrat',
                'clients' => 'Klijenti',
            ];
    
            $data['query'] = $this->getHttpQuery($data['dates_data'], [
                'tab' => request('tab', 'documents'),
                'status' => request('status', 'in_process'),
            ]);
            unset($data['query']['start'], $data['query']['end']);
    
            if ($data['query']['tab'] == 'documents') {
                $data['statuses'] = [
                    'in_process' => get_codebook_opts('document_status')->where('code', 'in_process')->first()->name,
                    'for_invoicing' => get_codebook_opts('document_status')->where('code', 'for_invoicing')->first()->name,
                ];
                
                $document = new Document();
                $document->limit = null;
                $document->typeId = ['order'];
                $document->statusId = $data['query']['status'];
                $document->stockId = $this->getUser()->rPerson->stock_id;
                $documents = $this->parseDocumentsForDashboard($document->relation(['rClient', 'rClient.rSalesmanPerson', 'rPaymentType'])->getAll())->groupBy('status');
    
                $data['user_documents'] = $documents;
            }
    
            if ($data['query']['tab'] == 'return') {
                $document = new Document();
                $document->limit = null;
                $document->typeId = ['return'];
                $document->statusId = ['in_process'];
                $documents = $this->parseDocumentsForDashboard($document->relation(['rClient', 'rClient.rSalesmanPerson', 'rPaymentType'])->getAll());
                
                $data['user_returns'] = $documents->groupBy('status');
            }
    
            if ($data['query']['tab'] == 'clients') {
                $client = new Client();
                $client->limit = null;
                $client->statusId = ['pending'];
                $client->stockId = $this->getUser()->rPerson->stock_id;
                $data['user_clients'] = $client->relation(['rSalesmanPerson'])->getAll()->groupBy('status');
            }
        }
    }
}
