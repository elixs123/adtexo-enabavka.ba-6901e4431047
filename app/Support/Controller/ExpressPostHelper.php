<?php

namespace App\Support\Controller;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Trait ExpressPostHelper
 *
 * @package App\Support\Controller
 */
trait ExpressPostHelper
{
    /**
     * @param \App\Document $document
     * @return array
     */
    private function parseDocumentForExpressPost($document)
    {
        // $delivery_cost = calcDeliveryCost($document->delivery_type, $document->rStock->country_id, ($document->useMpcPrice() ? getPriceWithoutVat($document->total_discounted, $document->tax_rate) : $document->subtotal_discounted), $document->delivery_cost); // @Check
        $delivery_cost = $document->fiscal_delivery_price;
        
        return [
            'collies_number' => $document->package_number,
            'weight' => $document->weight,
            'ref_1' => config('express_post.document_ref_prefix').': ' . $document->id . '/' .$document->created_at->format('Y'),
            'ref_2' => $document->date_of_order->format('d.m.Y.'),
            'ref_3' => null,
    
            'shipping_name' => isset($document->shipping_data['shipping_name']) ? $document->shipping_data['shipping_name'] : $document->shipping_data['name'],
            'shipping_postal_code' => $document->shipping_data['postal_code'],
            'shipping_address' => $document->shipping_data['address'],
            'shipping_city' => $document->shipping_data['city'],
            'shipping_country' => $this->parseCountryCodeForExpressPost($document->shipping_data['country']),
            'shipping_phone' => $document->shipping_data['phone'],
            'shipping_email' => $document->shipping_data['email'],
    
            'sender_name' => $document->rStock->original_name,
            'sender_postal_code' => $document->rStock->postal_code,
            'sender_address' => $this->getStockAddress($document->rStock),
            'sender_city' => $document->rStock->city,
            'sender_country' => $this->parseCountryCodeForExpressPost($document->rStock->country_id),
            'sender_phone' => $document->rStock->phone,
            'sender_email' => $document->rStock->email,
            
            'return_document' => !$document->isCashPayment(),
            
            'remark_delivery' => $document->note_express_post ?: '-',
            'remark_pickup' => $document->note_for_express_post ?: '-',
    
            'cod_amount' => $document->isCashPayment() ? $document->total_discounted + $delivery_cost : null,
        ];
    }
    
    /**
     * @param string $countryCode
     * @return string
     */
    private function parseCountryCodeForExpressPost($countryCode)
    {
        if (($countryCode == 'Bosna i Hercegovina') || ($countryCode == 'bih')) {
            return 'BA';
        }
        
        if (($countryCode == 'Srbija') || ($countryCode == 'srb')) {
            return 'RS';
        }
    
        return 'BA';
    }
    
    /**
     * @param \App\Stock $stock
     * @return string
     */
    private function getStockAddress($stock)
    {
        if (config('app.env') == 'production') {
            return $stock->address;
        }
        
        if ($stock->country_id == 'bih') {
            return 'Rajlovačka cesta bb';
        }
        
        return 'SAVE KOVAČEVIĆA 73';
    }
    
    /**
     * @param $express_post_type
     * @return \App\Libraries\Api\ExpressOne|mixed
     */
    private function getExpressPostObject($express_post_type)
    {
        $name = '\\App\\Libraries\\Api\\'.ucfirst(camel_case($express_post_type));
        return new $name();
    }
    
    /**
     * @param string $base64response
     * @return string
     */
    private function saveLabelResponseToPdf($base64response)
    {
        $path = 'assets/files/labels';
    
        $filename = time().'_'.auth()->id().'_labels.pdf';
        
        $data = base64_decode($base64response);
        
        return $this->saveResponseToPdf($path, $filename, $data);
    }
    
    /**
     * @param string $base64response
     * @return string
     */
    private function savePickupResponseToPdf($base64response)
    {
        $path = 'assets/files/pickups';
    
        $filename = time().'_'.auth()->id().'_pickups.pdf';
        
        $data = base64_decode($base64response);
        
        return $this->saveResponseToPdf($path, $filename, $data);
    }
    
    /**
     * @param string $path
     * @param string $filename
     * @param string $data
     * @return string
     */
    private function saveResponseToPdf($path, $filename, $data)
    {
        if (!File::isDirectory(public_path($path))) {
            File::makeDirectory(public_path($path));
        }
        
        file_put_contents(public_path($path.'/'.$filename), $data);
        
        return $path.'/'.$filename;
    }
    
    /**
     * @param \App\Document|mixed $document
     * @param \App\DocumentExpressPost|mixed $express_post
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    private function shipmentTraces($document, $express_post)
    {
        $tracking_number = $express_post->tracking_number_value;
    
        $express_post_api = $this->getExpressPostObject($express_post->express_post_type);
        $express_post_api->countryId = $express_post->rStock->country_id;
    
        $traces = $express_post_api->getShipmentTraces($tracking_number);
        // Log::info('TRACES: Document '.$document->id);
        $this->add2log($document->id, 'TRACES: Document '.$document->id);
        if (!$express_post_api->hasErrors()) {
            // Log::info('TRACES: Response '.json_encode($traces));
            $this->add2log($document->id, 'TRACES: Response '.json_encode($traces));
            
            $shipment_traces = $express_post_api->mapShipmentTracesParams($traces);
            $this->add2log($document->id, 'TRACES: Mapped '.json_encode($shipment_traces));
        
            // Log::info('TRACES: Status '.$shipment_traces['status']);
            $this->add2log($document->id, 'TRACES: Status '.$shipment_traces['status']);
            if (!is_null($shipment_traces['status'])) {
                DB::transaction(function () use ($document, $express_post, $shipment_traces) {
                    $document->update([
                        'status' => $shipment_traces['status'],
                    ]);
        
                    $express_post->update([
                        'traces' => $shipment_traces,
                        'status' => $shipment_traces['status'],
                        'delivered_at' => ($shipment_traces['status'] == 'delivered') ? Carbon::createFromFormat('d.m.Y H:i:s', $shipment_traces['delivery_date']) : null,
                    ]);
                });
            }
        } else {
            // Log::info('TRACES: Error '.json_encode($express_post_api->getErrors()));
            $this->add2log($document->id, 'TRACES: Error '.json_encode($express_post_api->getErrors()));
        }
        
        return $traces;
    }
    
    /**
     * @param int $documentId
     * @param string $message
     * @return void
     */
    private function add2log($documentId, $message)
    {
        $path = storage_path('logs/express-post/'.$documentId.'.log');
    
        file_put_contents($path, now()->toDateTimeString().': '.$message.PHP_EOL, FILE_APPEND);
    }
}
