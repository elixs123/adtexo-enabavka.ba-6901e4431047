<?php

namespace App\Support\Controller\Dashboard;

use App\Client;
use App\Document;
use App\Person;

/**
 * Trait DashboardSupervisorHelper
 *
 * @package App\Support\Controller\Dashboard
 */
trait DashboardSupervisorHelper
{
    /**
     * @param array $data
     * @return void
     */
    private function getSupervisorData(&$data)
    {
        if (userIsSupervisor()) {
            $data['dates_data'] = $this->getDatesData();
    
            $data['main_tabs'] = [
                'salesmen' => 'Komercijalisti',
                'express_post' => 'Brza pošta',
            ];
    
            $data['query'] = $this->getHttpQuery($data['dates_data'], [
                'tab' => request('tab', 'salesmen'),
            ]);
            
            $client = new Client();
            $client->limit = null;
            $client->statusId = 'active';
            $client->isLocation = true;
            $this->scopeClientPersonId($client);
            $clients = $client->getAll()->keyBy('id')->transform(function($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->full_name,
                    'salesman_person_id' => $client->salesman_person_id,
                ];
            })->toArray();
    
            if ($data['query']['tab'] == 'salesmen') {
                $person = new Person();
                $person->limit = null;
                $person->includeIds = collect($clients)->pluck('salesman_person_id')->unique()->toArray();
                $data['user_persons'] = $person->relation([], true)->getAll()->pluck('name', 'user_id')->toArray();
                
                $created_by = request('created_by');
                
                $document = new Document();
                $document->limit = null;
                $document->typeId = ['preorder', 'order'];
                $document->startDate = $data['dates_data']['start_date']->toDateString();
                $document->endDate = $data['dates_data']['end_date']->toDateString();
                $document->createdBy = $created_by ? $created_by : null;
                $document->clientId = collect($clients)->pluck('id')->unique()->toArray();
                $document->statusId = ['in_warehouse', 'warehouse_preparing', 'for_invoicing', 'invoiced', 'completed', 'express_post', 'shipped', 'express_post_in_process', 'delivered', 'retrieved'];
                
                $persons = $data['user_persons'];
                $user_id = $this->getUserId();
                $documents = $this->parseDocumentsForDashboard($document->relation(['rClient', 'rPaymentType'])->getAll())->filter(function($document) use ($persons) {
                    return isset($persons[$document->created_by]);
                })->reject(function($document) use ($user_id) {
                    return $document->created_by == $user_id;
                })->reject(function($document) {
                    return $document->rClient->status == 'inactive';
                });
                $data['user_documents'] = $documents->groupBy('created_by')->map(function ($documents) {
                    $data = [
                        'preorder' => 0,
                        'order' => 0,
                    ];
                    
                    foreach ($documents as $document) {
                        $data[$document->type_id] += $document->dashboard_total_value;
                    }
                    
                    return $data;
                })->toArray();
                
                $data['user_total'] = [
                    'preorder' => $documents->where('type_id', 'preorder')->where('status', 'completed')->sum('dashboard_total_value'),
                    'order' => $documents->where('type_id', 'order')->whereIn('status', $document->statusId)->sum('dashboard_total_value'),
                ];
            }
    
            if ($data['query']['tab'] == 'express_post') {
                $data['query']['status'] = request('status', 'shipped');
        
                $data['express_post_statuses'] = $this->getExpressPostStatuses();
                unset($data['express_post_statuses']['invoiced'], $data['express_post_statuses']['express_post']);
        
                $document = new Document();
                $document->limit = null;
                $document->statusId = $data['query']['status'];
                $document->typeId = 'order';
                $document->startDate = $data['dates_data']['start_date']->toDateString();
                $document->endDate = $data['dates_data']['end_date']->toDateString();
                $document->clientId = collect($clients)->pluck('id')->unique()->toArray();
                $data['express_post_documents'] = $document->relation(['rClient', 'rClient.rSalesmanPerson', 'rExpressPost', 'rDeliveryType'], true)->getAll();
            }
        }
    }
}
