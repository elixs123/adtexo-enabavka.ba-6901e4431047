<?php

namespace App\Support\Controller;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Collections\SheetCollection;

/**
 * Trait PaymentHelper
 *
 * @package App\Support\Controller
 */
trait PaymentHelper
{
    /**
     * @param \App\Payment|mixed $payment
     * @return \Maatwebsite\Excel\Collections\RowCollection
     */
    private function readPaymentFile($payment)
    {
        $excel = Excel::load(storage_path(config('file.payment.path').'/'.$payment->file), function($reader) {
            $reader->noHeading();
    
            // $reader->setSeparator('-');
    
            $reader->ignoreEmpty();
        })->get();
        
        if (is_a($excel, SheetCollection::class)) {
            $excel = $excel->get(0);
        }
    
        $excel->forget(0)->all();
        
        return $excel;
    }
    
    /**
     * @param \App\Payment|mixed $payment
     * @param \Maatwebsite\Excel\Collections\RowCollection|mixed $rows
     * @return \Illuminate\Support\Collection
     */
    private function parsePaymentFileWithConfig($payment, $rows)
    {
        $parsed = [];
        
        $ship_number_prefix = $this->getShipNumberPrefix($payment->currency);
        
        foreach ($rows as $row) {
            $data = [];
            
            foreach ($payment->config as $key => $value) {
                if (isset($row[$value])) {
                    $cast = $this->castPaymentData($key, $row[$value], $ship_number_prefix);
                    
                    if (($key == 'document_id') && (is_null($cast) || ($cast <= 0))) {
                        $data = [];
                    
                        break;
                    }
                    
                    $data[$key] = $cast;
                }
            }
            
            if (count($data)) {
                $parsed[] = $data;
            }
        }
        
        return collect($parsed);
    }
    
    /**
     * @param string $key
     * @param mixed $value
     * @param string $shipNumberPrefix
     * @return float|int|mixed
     */
    private function castPaymentData($key, $value, $shipNumberPrefix)
    {
        if ($key == 'shipment_number') {
            return intval($value);
        } else if ($key == 'document_id') {
            $space = strpos($value, ' ');
    
            if ($space === false) {
                return null;
            }
    
            return (int) substr($value, $space);
        } else if ($key == 'amount') {
            $value = str_replace(',', '.', $value);
            
            return floatval($value);
        }
        
        return $value;
    }
    
    /**
     * @param $currency
     * @return string
     */
    private function getShipNumberPrefix($currency)
    {
        if ($currency == 'KM') {
            return '070001';
        }
        
        if ($currency == 'RSD') {
            return '688001';
        }
    
        return '';
    }
    
    /**
     * @param \App\Payment|mixed $payment
     */
    private function syncPaymentItems($payment)
    {
        $rows = $this->readPaymentFile($payment);
    
        $items = $this->parsePaymentFileWithConfig($payment, $rows);
        
        $payment->rPaymentItems()->delete();

        $payment->rPaymentItems()->createMany($items->toArray());
        
        $this->updateTotalPayments($payment, $items->sum('amount'));
        
        $payment_items_documents = $payment->rPaymentItems()->get()->pluck('document_id')->toArray();
        
        $this->updateTotalDocuments($payment, $payment_items_documents, $payment->type);
    }
    
    /**
     * @param \App\Payment|mixed $payment
     * @param float $totalPayments
     */
    private function updateTotalPayments($payment, $totalPayments)
    {
        $payment->update([
            'total_payments' => $totalPayments,
        ]);
    }
    
    /**
     * @param \App\Payment|mixed $payment
     * @param array $documentIds
     */
    private function updateTotalDocuments($payment, $documentIds, $paymentType)
    {
        $total_documents = DB::table('documents')->whereIn('id', $documentIds)->get(['total_discounted', 'delivery_cost']);
        
        if ($paymentType == 'express_post') {
            $total_documents = $total_documents->sum(function($document) {
                return round($document->total_discounted + $document->delivery_cost, 2);
            });
        } else {
            $total_documents = $total_documents->sum(function($document) {
                return round($document->total_discounted, 2);
            });
        }
    
        $payment->update([
            'total_documents' => $total_documents,
        ]);
    }
}
