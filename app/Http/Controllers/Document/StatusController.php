<?php

namespace App\Http\Controllers\Document;

use App\Document;
use App\DocumentExpressPost;
use App\Http\Controllers\Controller;
use App\Libraries\Api\PantheonApi;
use App\Libraries\PantheonHelper;
use App\Support\Controller\DocumentStatusHelper;
use App\Support\Controller\ExpressPostHelper;
use Illuminate\Http\Request;

/**
 * Class StatusController
 *
 * @package App\Http\Controllers\Document
 */
class StatusController extends Controller
{
    use ExpressPostHelper, DocumentStatusHelper;
    
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * StatusController constructor.
     *
     * @param \App\Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
    
    /**
     * Change status.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function change(Request $request)
    {
        $status = $request->get('s');
        $document_type = $request->get('t');
        $document_ids = $request->get('d', []);
        
        $this->document->statusId = $this->getDocumentPreviousStatus($status, $document_type);
        $this->document->includeIds = $document_ids;
        $this->document->limit = null;
        $this->document->relation(['rExpressPost', 'rStock', 'rDocumentProduct']);
        $documents = $this->document->getAll();
    
        $items = [];
        $failed = [];
        $this->dbTransaction(function () use ($documents, $status, $request, &$failed, &$items) {
            foreach ($documents as $document) {
                if (in_array($status, ['shipped'])) {
                    continue;
                }
                
                $attributes = [
                    'status' => $status,
                ];
        
                if ($status == 'in_warehouse') {
                    $attributes['date_of_processing'] = now()->toDateTimeString();
                }
                
                if ($status == 'for_invoicing') {
                    $attributes['date_of_warehouse'] = now()->toDateTimeString();
                    $attributes['date_of_delivery'] = now()->toDateString();
                    
                    // if ($document->buyer_data['country_id'] == 'bih') {
                    //     if ($document->buyer_data['type_id'] == 'business_client') {
                            $pantheon_api = new PantheonApi();
                            $pantheon_api->responseAssoc = true;
                            
                            $response = $pantheon_api->insertOrder(PantheonHelper::mapDocumentForRequest($document));
                            
                            if ($pantheon_api->hasErrors()) {
                                $failed[] = "Document #{$document->id} - Pantheon response: ".(json_encode($pantheon_api->getErrors()));
    
                                continue;
                            } else if (is_null($response)) {
                                $failed[] = "Document #{$document->id} - Pantheon response is NULL ";
    
                                continue;
                            } else {
                                $order = PantheonHelper::createOrderFromResponse($response, $document->id);
                                
                                PantheonHelper::syncDocumentWithOrder($document, $order->id);
                            }
                        // }
                    // }
                }
        
                if ($status == 'invoiced') {
                    $attributes['date_of_payment'] = now()->addDays($document->payment_period_in_days)->toDateString();
                }
    
                if ($status == 'canceled') {
                    if (!$document->canBeCanceled()) {
                        $failed[] = "Document #{$document->id} can not be canceled!";
                        
                        continue;
                    }
                }
                
                if ($status == 'express_post') {
                    $express_post_type = $request->get('express_post_type');
                    
                    $express_post_api = $this->getExpressPostObject($express_post_type);
                    $express_post_api->countryId = $document->rStock->country_id;
    
                    $document->note_for_express_post = $request->get('note_for_express_post', '-');
                    $shipmentData = $express_post_api->createShipment($this->parseDocumentForExpressPost($document));
                    
                    if ($express_post_api->hasErrors()) {
                        $failed[] = "Document #{$document->id} CreateShipment Failed: ".implode(', ', $express_post_api->getErrors());
                        continue;
                    }
                    
                    $shipment_id = $express_post_api->getShipmentId($shipmentData);
                    $tracking_number = $express_post_api->getTrackingNumber($shipmentData);
                    
                    $express_post_attributes = [
                        'stock_id' => $document->stock_id,
                        'express_post_type' => $express_post_type,
                        'shipment_id' => $shipment_id,
                        'tracking_number' => $tracking_number,
                        'picked_at' => now(),
                        'status' => 'express_post',
                    ];
                    if (is_null($document->rExpressPost)) {
                        $document->rExpressPost()->create($express_post_attributes);
                    } else {
                        $document->rExpressPost()->update($express_post_attributes);
                    }
                    
                    $document->rExpressPostEvents()->create([
                        'type' => 'createShipment',
                        'response' => $shipmentData,
                    ]);
                }
    
                if ($status == 'express_post_canceled') {
                    if (!is_null($document->rExpressPost)) {
                        $express_post_type = $document->rExpressPost->express_post_type;
        
                        $express_post_api = $this->getExpressPostObject($express_post_type);
                        $express_post_api->countryId = $document->rStock->country_id;
        
                        if ($document->rExpressPost->shipment_id) {
                            $express_post_api->cancelShipment($document->rExpressPost->shipment_id);
                            if ($express_post_api->hasErrors()) {
                                $failed[] = "Document #{$document->id} CancelShipment Failed: ".implode(', ', $express_post_api->getErrors());
                                continue;
                            }
    
                            $document->rExpressPost->update([
                                'status' => 'express_post_canceled',
                            ]);
                        }
                    }
                }
    
                if ($status == 'delivered') {
                    // @ToDo
                }
    
                if ($status == 'returned') {
                    // @ToDo
                }
    
                if ($status == 'retrieved') {
                    $document->rTakeover()->create([
                        'name' => $request->get('takeover_name'),
                        'picked_at' => now(),
                    ]);
                }
    
                unset($document->note_for_express_post);
                $document->update($attributes);
    
                $this->updateStock($document);
                
                $items[] = $document->uid;
            }
        });
    
        $this->dbTransaction(function () use ($documents, $status, $request, &$failed) {
            if ($status == 'shipped') {
                $documents_per_post = $documents->groupBy(function ($document) {
                    return $document->rExpressPost->express_post_type;
                });
                foreach ($documents_per_post as $express_post_type => $_documents) {
                    $express_post_api = $this->getExpressPostObject($express_post_type);
                    $express_post_api->countryId = $_documents->first()->rStock->country_id;
        
                    $shipment_ids = $_documents->map(function($document) {
                        return $document->rExpressPost->shipment_id;
                    })->toArray();
        
                    $labelData = $express_post_api->getShippingLabelsForShipments($shipment_ids);
                    if ($express_post_api->hasErrors()) {
                        $failed[] = "GetShippingLabelsForShipments Failed: ".implode(', ', $_documents->pluck('id')->toArray()).' - Error: '.implode(', ', $express_post_api->getErrors());
                        continue;
                    }
                    $labelPath = $this->saveLabelResponseToPdf($labelData->PdfDocument);
                    $labelData->PdfDocument = $labelPath;
        
                    $pickupData = $express_post_api->requestPickup($shipment_ids);
                    if ($express_post_api->hasErrors()) {
                        $failed[] = "RequestPickup Failed: ".implode(', ', $_documents->pluck('id')->toArray()).' - Error: '.implode(', ', $express_post_api->getErrors());
                        continue;
                    }
                    $pickupPath = $this->savePickupResponseToPdf($pickupData->PickupListDocument);
                    $pickupData->PickupListDocument = $pickupPath;
    
                    DocumentExpressPost::whereIn('document_id', $_documents->pluck('id')->toArray())->update([
                        'pdf_label_path' => $labelPath,
                        'pdf_pickup_path' => $pickupPath,
                        'status' => $status,
                        // 'picked_at' => Carbon::createFromTimeString($pickupData->PickupDate),
                    ]);
    
                    foreach ($_documents as $document) {
                        $document->rExpressPostEvents()->create([
                            'type' => 'shippingLabel',
                            'response' => $labelData,
                        ]);
    
                        $document->rExpressPostEvents()->create([
                            'type' => 'requestPickup',
                            'response' => $pickupData,
                        ]);
                    }
    
                    Document::whereIn('id', $_documents->pluck('id')->toArray())->update([
                        'status' => $status,
                    ]);
                }
            }
        });
        
        return $this->getSuccessJsonResponse([
            'status' => $status,
            'items' => $items,
            'failed' => $failed,
            'redirect' => ($status == 'invoiced') ? route('document.index', ['export' => 'xml']).'&'.implode('&', array_map(function($item) { return $item = 'd[]='.$item;}, $document_ids)) : '',
            'notification' => [
                'type' => 'success',
                'message' => trans('document.notifications.status', ['status' => get_codebook_opts('document_status')->where('code', $status)->first()->name]),
            ],
            'close_modal' => true,
        ]);
    }
    
    /**
     * @param string $status
     * @param string $documentType
     * @return string
     */
    private function getDocumentPreviousStatus($status, $documentType)
    {
        switch ($status) {
            case 'canceled' :
                if ($documentType == 'order') {
                    $previous_status = ['for_invoicing', 'in_warehouse'];
                } else if ($documentType == 'return') {
                    $previous_status = 'in_process';
                } else {
                    $previous_status = 'none';
                }
                break;
            case 'in_warehouse' :
            case 'completed' :
                $previous_status = 'in_process';
                break;
            case 'warehouse_preparing' :
                $previous_status = 'in_warehouse';
                break;
            case 'for_invoicing' :
                $previous_status = 'warehouse_preparing';
                break;
            case 'invoiced' :
                $previous_status = 'for_invoicing';
                break;
            case 'express_post' :
            case 'retrieved' :
                $previous_status = 'invoiced';
                break;
            case 'shipped' :
            case 'express_post_canceled' :
                $previous_status = 'express_post';
                break;
            case 'delivered' :
            case 'returned' :
                $previous_status = 'shipped';
                break;
            default :
                $previous_status = 'none';
            break;
        }
        
        return $previous_status;
    }
}
