<?php

namespace App\Support\Controller\Dashboard;

use App\Document;

/**
 * Trait DashboardWarehouseHelper
 *
 * @package App\Support\Controller\Dashboard
 */
trait DashboardWarehouseHelper
{
    /**
     * @param array $data
     * @return void
     */
    private function getWarehouseData(&$data)
    {
        if (userIsWarehouse()) {
            $data['tabs'] = [
                'in_warehouse' => get_codebook_opts('document_status')->where('code', 'in_warehouse')->first()->name,
                'for_invoicing' => get_codebook_opts('document_status')->where('code', 'for_invoicing')->first()->name,
                'express_post' => get_codebook_opts('document_status')->where('code', 'express_post')->first()->name,
                'personal_takeover' => get_codebook_opts('delivery_types')->where('code', 'personal_takeover')->first()->name,
            ];
            if (count(config('express_post.countries.'.scopedStock()->priceCountryId())) == 0) {
                unset($data['tabs']['express_post'], $data['tabs']['personal_takeover']);
                
                $data['tabs']['invoiced'] = get_codebook_opts('document_status')->where('code', 'invoiced')->first()->name;
            }
            
            $data['tab_active'] = request('tab', 'in_warehouse');
            
            $data['dates_data'] = $this->getDatesData();
            
            $data['query'] = [
                'start' => request('start', $data['dates_data']['start_date']->toDateString()),
                'end' => request('end', $data['dates_data']['end_date']->toDateString()),
                'status' => (in_array($data['tab_active'], ['for_invoicing'])) ? $data['tab_active'] : request('status', 'invoiced'),
            ];
            
            if ($data['tab_active'] == 'in_warehouse') {
                $data['query']['status'] = request('status', 'in_warehouse');
                
                $data['status'] = [
                    'in_warehouse' => get_codebook_opts('document_status')->where('code', 'in_warehouse')->first()->name,
                    'warehouse_preparing' => get_codebook_opts('document_status')->where('code', 'warehouse_preparing')->first()->name,
                ];
            } else if ($data['tab_active'] == 'express_post') {
                $data['status'] = [
                    'invoiced' => get_codebook_opts('document_status')->where('code', 'invoiced')->first()->name,
                    'express_post' => get_codebook_opts('document_status')->where('code', 'express_post')->first()->name,
                    'shipped' => get_codebook_opts('document_status')->where('code', 'shipped')->first()->name,
                    'express_post_in_process' => get_codebook_opts('document_status')->where('code', 'express_post_in_process')->first()->name,
                    'delivered' => get_codebook_opts('document_status')->where('code', 'delivered')->first()->name,
                    'returned' => get_codebook_opts('document_status')->where('code', 'returned')->first()->name,
                    'express_post_canceled' => get_codebook_opts('document_status')->where('code', 'express_post_canceled')->first()->name,
                ];
            } else if ($data['tab_active'] == 'personal_takeover') {
                $data['status'] = [
                    'invoiced' => get_codebook_opts('document_status')->where('code', 'invoiced')->first()->name,
                    'retrieved' => get_codebook_opts('document_status')->where('code', 'retrieved')->first()->name,
                ];
            }
            
            $relations = ['rClient', 'rClient.rSalesmanPerson', 'rPaymentType', 'rExpressPost', 'rDeliveryType', 'rTakeover'];
            
            $document = new Document();
            $document->limit = null;
            $document->typeId = ['order'];
            $document->statusId = $data['query']['status'];
            $document->startDate = $data['dates_data']['start_date']->toDateString();
            $document->endDate = $data['dates_data']['end_date']->toDateString();
            if (in_array($data['tab_active'], ['in_warehouse', 'for_invoicing'])) {
                //
            } else {
                if ($data['tab_active'] == 'express_post') {
                    $document->deliveryType = ['free_delivery', 'paid_delivery'];
                    
                    if ($data['query']['status'] == 'invoiced') {
                        if ($data['dates_data']['start_date']->toDateString() <= '2021-05-07') {
                            $document->startDate = '2021-05-08';
                        }
                    }
                } else if ($data['tab_active'] == 'personal_takeover') {
                    $document->deliveryType = ['personal_takeover'];
                }
            }
            $document->stockId = $this->getUser()->rPerson->stock_id;
            $document->deliveryDate = now()->toDateString();
            $documents = $this->parseDocumentsForDashboard($document->relation($relations)->getAll());
            
            $data['user_documents'] = $documents->groupBy('status');
        }
    }
}
