<?php

namespace App\Support\Controller\Dashboard;

use App\Document;

/**
 * Trait DashboardClientHelper
 *
 * @package App\Support\Controller\Dashboard
 */
trait DashboardClientHelper
{
    /**
     * @param array $data
     * @return void
     */
    private function getClientData(&$data)
    {
        if (userIsClient()) {
            $data['client'] = $this->getUser()->client;
            
            $document = new Document();
            $document->limit = 50;
            $document->typeId = ['order'];
            $document->clientId = is_null($this->getUser()->client) ? 1 : $this->getUser()->client->id;
            $documents = $this->parseDocumentsForDashboard($document->relation(['rClient', 'rClient.rSalesmanPerson', 'rPaymentType', 'rDocumentProduct'], true)->getAll());
            
            $data['user_documents'] = $documents->groupBy('status');
            
            if (userIsSalesAgent()) {
                $data['dates_data'] = $this->getDatesData();
                
                $document = new Document();
                $document->limit = null;
                $document->typeId = ['order'];
                $document->clientId = is_null($this->getUser()->client) ? 1 : $this->getUser()->client->id;
                $document->startDate = $data['dates_data']['start_date']->toDateString();
                $document->endDate = $data['dates_data']['end_date']->toDateString();
                $documents = $this->parseTotalsForDocuments($document->relation(['rDocumentProduct'])->getAll());
                
                $data['sales_documents'] = $documents;
            }
            
            $data['loyalty'] = [
                'orders' => $this->getTotalLoyaltyPoints(now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString(), 'order'),
                'returns' => $this->getTotalLoyaltyPoints(now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString(), 'return'),
            ];
            
            if (!is_null($this->getUser()->client) && !is_null($contract = $this->getUser()->client->rContract)) {
                $data['contract'] = [
                    'total_qty' => $contract->total_qty,
                    'total_bought' => $contract->total_bought,
                    'in_stock' => $contract->in_stock,
                ];
            }
        }
    }
}
