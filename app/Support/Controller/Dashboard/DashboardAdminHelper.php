<?php

namespace App\Support\Controller\Dashboard;

use App\Document;
use App\Person;

/**
 * Trait DashboardAdminHelper
 *
 * @package App\Support\Controller\Dashboard
 */
trait DashboardAdminHelper
{
    /**
     * @param array $data
     * @return void
     */
    private function getAdminData(&$data, $tabActive = 'salesmen')
    {
        if (userIsAdmin()) {
            $data['countries'] = $this->getCountries();
            
            $data['dates_data'] = $this->getDatesData();
            
            $data['main_tabs'] = [
                'salesmen' => 'Komercijalisti',
                'documents' => 'Dokumenti',
                'express_post' => 'Brza pošta',
                'personal_takeover' => get_codebook_opts('delivery_types')->where('code', 'personal_takeover')->first()->name,
                'payments' => 'Gotovisnki nalozi',
            ];
            
            $data['query'] = $this->getHttpQuery($data['dates_data'], [
                'tab' => request('tab', $tabActive),
                'country' => request('country', 'bih'),
                'type_id' => request('type_id', 'order'),
            ]);
            
            $data['currency'] = ($data['query']['country'] == 'bih') ? 'KM' : 'RSD';
    
            if ($data['query']['tab'] == 'salesmen') {
                $person = new Person();
                $person->limit = null;
                $person->typeId = 'salesman_person';
                $person->countryId = $data['query']['country'];
                $data['user_persons'] = $person->relation(['rStock.rCountry'], true)->getAll()->pluck('name', 'user_id')->toArray();
                
                $document = new Document();
                $document->limit = null;
                $document->typeId = ['preorder', 'order'];
                $document->startDate = $data['dates_data']['start_date']->toDateString();
                $document->endDate = $data['dates_data']['end_date']->toDateString();
                $document->createdBy = array_keys($data['user_persons']);
                $document->statusId = ['in_warehouse', 'warehouse_preparing', 'for_invoicing', 'invoiced', 'completed', 'express_post', 'shipped', 'express_post_in_process', 'delivered', 'retrieved'];
                $documents = $document->relation(['rClient'], true)->getAll()->reject(function($document) {
                    return $document->rClient->status == 'inactive';
                });
                $data['person_documents'] = $documents->groupBy('created_by')->map(function ($documents) {
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
                    'order' => $documents->where('type_id', 'order')->sum('dashboard_total_value'),
                ];
            }
    
            if ($data['query']['tab'] == 'documents') {
                $document = new Document();
                $document->limit = null;
                $document->statusId = $data['query']['status'];
                $document->typeId = $data['query']['type_id'];
                $document->startDate = $data['dates_data']['start_date']->toDateString();
                $document->endDate = $data['dates_data']['end_date']->toDateString();
                $document->countryId = $data['query']['country'];
                $data['user_documents'] = $document->relation(['rClient', 'rStock.rCountry', 'rClient.rSalesmanPerson', 'rPaymentType'], true)->getAll()->reject(function($document) {
                    return $document->rClient->status == 'inactive';
                });
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
                $document->countryId = $data['query']['country'];
                $data['express_post_documents'] = $document->relation(['rClient', 'rClient.rSalesmanPerson', 'rExpressPost', 'rDeliveryType'], true)->getAll();
            }
    
            if ($data['query']['tab'] == 'personal_takeover') {
                $data['query']['status'] = 'retrieved';
                
                $document = new Document();
                $document->limit = null;
                $document->statusId = $data['query']['status'];
                $document->typeId = 'order';
                $document->deliveryType = 'personal_takeover';
                $document->startDate = $data['dates_data']['start_date']->toDateString();
                $document->endDate = $data['dates_data']['end_date']->toDateString();
                $document->countryId = $data['query']['country'];
                $data['user_documents'] = $document->relation(['rClient', 'rStock.rCountry', 'rClient.rSalesmanPerson', 'rDeliveryType', 'rTakeover'], true)->getAll()->reject(function($document) {
                    return $document->rClient->status == 'inactive';
                });
            }
    
            if ($data['query']['tab'] == 'payments') {
                $document = new Document();
                $document->limit = null;
                $document->statusId = ['delivered', 'retrieved'];
                $document->typeId = 'order';
                $document->startDate = $data['dates_data']['start_date']->toDateString();
                $document->endDate = $data['dates_data']['end_date']->toDateString();
                $document->countryId = $data['query']['country'];
                // $document->deliveryType = 'personal_takeover';
                $data['user_documents'] = $document->relation(['rExpressPost', 'rPaymentItem', 'rDeliveryType'], true)->getAll();
                
                $data['payment_totals'] = [
                    'total' => $this->sumPaymentDocuments($data['user_documents']),
                    'payed' => $this->sumPaymentDocuments($data['user_documents']->where('is_payed', 1)),
                    'unpaid' => $this->sumPaymentDocuments($data['user_documents']->where('is_payed', 0)),
                ];
            }
        }
    }
    
    /**
     * @param \Illuminate\Database\Eloquent\Collection $documents
     * @return float
     */
    private function sumPaymentDocuments($documents)
    {
        return $documents->sum(function($document) {
            if ($document->delivery_type == 'personal_takeover') {
                return round($document->total_discounted, 2);
            }
    
            return round($document->total_discounted + $document->delivery_cost, 2);
        });
    }
}
