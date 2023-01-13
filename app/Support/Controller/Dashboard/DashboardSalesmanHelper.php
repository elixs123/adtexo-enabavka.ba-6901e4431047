<?php

namespace App\Support\Controller\Dashboard;

use App\Document;

/**
 * Trait DashboardSalesmanHelper
 *
 * @package App\Support\Controller\Dashboard
 */
trait DashboardSalesmanHelper
{
    /**
     * @param array $data
     * @return void
     */
    private function getSalesmanData(&$data)
    {
        if (userIsSalesman()) {
            $data['dates_data'] = $this->getDatesData();
            
            $data['main_tabs'] = [
                'routes' => 'Rute',
                'express_post' => 'Brza pošta',
            ];
    
            $data['query'] = $this->getHttpQuery($data['dates_data'], [
                'tab' => request('tab', 'routes'),
            ]);
            unset($data['query']['start'], $data['query']['end']);
    
            if ($data['query']['tab'] == 'routes') {
                $data['week_data'] = $this->getWeekData();
                
                $data['user_routes'] = $this->getUserRoutes($data['week_data']['date'], $data['week_data']['week_num']);
                
                $document = new Document();
                $document->limit = null;
                $document->dateOfOrder = $data['week_data']['days'];
                $document->typeId = ['preorder', 'order'];
                $this->scopeDocumentCreatedBy($document);
                
                $documents = $this->parseDocumentsForDashboard($document->relation(['rType', 'rClient', 'rPaymentType', 'rStatus'])->getAll())->reject(function($document) {
                    return $document->rClient->status == 'inactive';
                });
                $data['user_documents'] = $documents->groupBy(function($document) {
                    return $document->date_of_order->toDateString();
                })->map(function ($documents) {
                    return $documents->groupBy('client_id')->map(function($documents) {
                        $data = [
                            'preorder' => [],
                            'order' => [],
                        ];
                        
                        foreach ($documents as $document) {
                            $data[$document->type_id][] = [
                                'id' => $document->id,
                                'name' => $document->rType->name,
                                'background_color' => $document->rType->background_color,
                                'color' => $document->rType->color,
                                'value' => $document->total_discounted_value,
                                'currency' => $document->currency,
                                'status' => $document->status,
                                'status_name' => $document->rStatus->name,
                            ];
                        }
                        
                        return $data;
                    });
                })->toArray();
                
                $data['user_total'] = [
                    'preorder' => $documents->where('type_id', 'preorder')->where('status', 'completed')->sum('dashboard_total_value'),
                    'order' => $documents->where('type_id', 'order')->whereIn('status', ['in_warehouse', 'for_invoicing', 'invoiced'])->sum('dashboard_total_value'),
                ];
            }
    
            if ($data['query']['tab'] == 'express_post') {
                $data['query']['status'] = request('status', 'shipped');
    
                $data['express_post_statuses'] = $this->getExpressPostStatuses();
                unset($data['express_post_statuses']['invoiced'], $data['express_post_statuses']['express_post']);
    
                $document = new Document();
                $document->limit = 50;
                $document->statusId = $data['query']['status'];
                $document->typeId = 'order';
                // $document->startDate = $data['dates_data']['start_date']->toDateString();
                // $document->endDate = $data['dates_data']['end_date']->toDateString();
                $this->scopeDocumentCreatedBy($document);
                $data['express_post_documents'] = $document->relation(['rClient', 'rClient.rSalesmanPerson', 'rExpressPost', 'rDeliveryType'], true)->getAll();
            }
        }
    }
}
