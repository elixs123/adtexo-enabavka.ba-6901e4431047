<?php

namespace App\Support\Controller;

use App\Client;
use App\Document;
use App\Person;
use Illuminate\Support\Facades\DB;

/**
 * Trait DashboardHelper
 *
 * @package App\Support\Controller
 */
trait DashboardHelper
{
    /**
     * @return array
     */
    private function getWeekData()
    {
        $weeks = now()->endOfYear()->format('W');
        $current = request('week', now()->weekOfYear);
        
        $week_num = $current % 4;
        
        $data = [
            'weeks' => [],
            'days' => [],
            'current' => $current,
            'prev' => $current - 1,
            'next' => $current + 1,
            'date' => now(),
            'max' => $weeks,
            'week_num' => $week_num ? $week_num : 4,
        ];
        
        for ($i = 1; $i <= $weeks; $i++) {
            $date = getDateFromWeekNumber($i);
            
            $data['weeks'][$i] = $i.'. '.trans('skeleton.vars.date.week').' '.' ('.$date->copy()->startOfWeek()->format('d.m.Y').' - '.$date->copy()->endOfWeek()->format('d.m.Y').')';
            
            if ($i == $current) {
                $data['date'] = $date;
            }
        }

        $startOfWeek = $data['date']->copy()->startOfWeek();
        for ($i = 0; $i < 7; $i++) {
            $data['days'][] = ($i == 0) ? $startOfWeek->toDateString() : $startOfWeek->addDay()->toDateString();
        }
        
        return $data;
    }
    
    /**
     * @param array $data
     * @return void
     */
    private function getInvoicingData(&$data)
    {
        if (userIsWarehouse($data)) {
            $document = new Document();
            $document->limit = 100;
            $document->typeId = ['order'];

            $document->startDate = (new \DateTime('now'))->modify('-1 months')->format('Y-m-d');
            $document->endDate = (new \DateTime('now'))->format('Y-m-d');
            $document->statusId = ['for_invoicing', 'returned', 'reversed', 'invoiced'];
            $document->stockId = $this->getUser()->rPerson->stock_id;
            $documents = $this->parseDocumentsForDashboard($document->relation(['rClient', 'rClient.rSalesmanPerson', 'rPaymentType'])->getAll());
            $for_invoicing = $documents;

            $document->startDate = null;
            $document->endDate = null;
            $document->fiscalReceiptFrom = new \DateTime('now');
            $document->fiscalReceiptTo = new \DateTime('now');
            $document->fiscalReceiptFrom->modify('-1 days');
            $document->fiscalReceiptTo->modify('+1 days');
            $document->statusId = ['invoiced', 'retrieved', 'express_post', 'delivered', 'returned', 'reversed'];
            $documents = $this->parseDocumentsForDashboard($document->relation(['rClient', 'rClient.rSalesmanPerson', 'rPaymentType'])->getAll());

            $invoiced = $documents;

            $allDocuments = $for_invoicing->merge($invoiced)->groupBy('status');

            $data['user_documents'] = $allDocuments;

        }
    }
    
    /**
     * @param \Illuminate\Database\Eloquent\Collection $documents
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function parseDocumentsForDashboard($documents)
    {
        $document_types = get_codebook_opts('document_type')->keyBy('code')->toArray();
        
        return $documents->reject(function($document) {
            return $document->rClient->status == 'inactive';
        })->transform(function($document) use ($document_types) {
            $document->type = [
                'name' => $document_types[$document->type_id]['name'],
                'background_color' => $document_types[$document->type_id]['background_color'],
                'color' => $document_types[$document->type_id]['color'],
            ];
            
            return $document;
        });
    }
    
    /**
     * @param \Illuminate\Database\Eloquent\Collection $documents
     * @return array
     */
    private function parseTotalsForDocuments($documents)
    {
        $data = [];
        
        $data['documents'] = $documents->count();
        
        $data['vpc'] = $documents->map(function($document) {
            return $document->rDocumentProduct->sum('vpc');
        })->sum();
        
        $data['vpc_discounted'] = $documents->map(function($document) {
            return $document->rDocumentProduct->sum('vpc_discounted');
        })->sum();
        
        $data['vpc_difference'] = $data['vpc'] - $data['vpc_discounted'];
        
        return $data;
    }
    
    /**
     * @param string $start
     * @param string $end
     * @param string $documentType
     * @return int
     */
    private function getTotalLoyaltyPoints($start, $end, $documentType)
    {
        $data = DB::select("SELECT SUM(dp.total_loyalty_points) AS points FROM document_products AS dp
                LEFT JOIN documents AS d ON d.id = dp.document_id
                WHERE d.date_of_order >= ? AND d.date_of_order <= ?
                AND d.status IN ('invoiced', 'completed')
                AND d.type_id = ?
                AND d.client_id = ?", [$start, $end, $documentType, is_null($this->getUser()->client) ? 1 : $this->getUser()->client->id]);
    
        if (isset($data[0]) && !is_null($data[0]->points)) {
            return abs($data[0]->points);
        }
        
        return 0;
    }
    
    /**
     * @param array $dates
     * @return array
     */
    private function getHttpQuery($dates, $additional = [])
    {
        return array_merge([
            'start' => request('start', $dates['start_date']->toDateString()),
            'end' => request('end', $dates['end_date']->toDateString()),
            'status' => request('status', 'for_invoicing'),
        ], $additional);
    }
    
    /**
     * @return array
     */
    private function getCountries()
    {
        return get_codebook_opts('countries')->pluck('name', 'code')->toArray();
    }
}
