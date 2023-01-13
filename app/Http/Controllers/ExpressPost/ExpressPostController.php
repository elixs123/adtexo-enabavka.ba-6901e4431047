<?php

namespace App\Http\Controllers\ExpressPost;

use App\Document;
use App\Http\Controllers\Controller;
use App\Libraries\Api\ExpressOne as ExpressPost;
use App\Support\Controller\ExpressPostHelper;

/**
 * Class ExpressPostController
 *
 * @package App\Http\Controllers\ExpressPost
 */
class ExpressPostController extends Controller
{
    use ExpressPostHelper;
    
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * @var ExpressPost
     */
    private $expressPost;
    
    /**
     * ExpressPostController constructor.
     *
     * @param \App\Document $document
     */
    public function __construct(Document $document, ExpressPost $expressPost)
    {
        $this->document = $document;
        $this->expressPost = $expressPost;
    }
    
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // $this->expressPost->countryId = 'bih';
		// $traces = $this->expressPost->getShipmentTraces('07000117346201');
		// dd($traces);
		
		$this->expressPost->countryId = 'srb';
		$traces = $this->expressPost->getShipmentTraces('68800118578409');
		// dd($traces);
		
        // echo json_encode($this->expressPost->mapShipmentTracesParams($traces));
		dd($this->expressPost->mapShipmentTracesParams($traces));
		
        ############ Step 1
		/*
         $cities = file(storage_path('cargo.csv'));
        
         foreach($cities as $city)
         {
             list($code, $name) = explode(',', $city);
        
             $data = [
                 'postal_code' => trim($code),
                 'name' => trim($name),
                 'country_id' => 'bih'
             ];
        
             \App\City::create($data);
        
             dump($data);
         }
         exit;
		*/
        ############ Step 2
        // $cities = \App\City::where('country_id', 'bih')->get();
        //
        // foreach($cities as $city)
        // {
        //     \App\Client::where('country_id', 'bih')->where('postal_code', $city->postal_code)->update(['city' => $city->name]);
        // }
        // exit;
    
        ############ Step 3
        // $xml = simplexml_load_file('https://usersupport.dexpress.rs:4321/GetCities/20000101000000');
        //
        // foreach($xml as $city)
        // {
        //     if($city->PttNo != 18000)
        //     {
        //         $data = [
        //             'postal_code' => trim($city->PttNo),
        //             'name' => trim($city->CityName),
        //             'country_id' => 'srb'
        //         ];
        //
        //         \App\City::create($data);
        //
        //         dump($data);
        //     }
        // }
        // exit;
		
		// $cities = $this->expressPost->getCities();
		//
		// foreach($cities->Intervals as $city)
		// {
        //        $data = [
        //              'postal_code' => trim($city->PostalCode),
        //              'name' => trim($city->Name),
        //              'country_id' => 'srb'
        //         ];
        //
        //         \App\City::create($data);
        //
        //         dump($data);
		// }
		
        ############ Step 4
         // $cities = \App\City::where('country_id', 'srb')->get();
         //
         // foreach($cities as $city)
         // {
         //     \App\Client::where('country_id', 'srb')->where('postal_code', $city->postal_code)->update(['city' => $city->name]);
         // }
         // exit;

        // $data = [
        //     'collies_number' => 1,
        //     'weight' => 2,
        //     'ref_1' => 'Broj računa:' . str_random(10),
        //     'ref_2' => 'ref_2',
        //     'ref_3' => 'ref_3',
        //
        //     'shipping_name' => 'Emir Agić',
        //     'shipping_postal_code' => '71000',
        //     'shipping_address' => 'Hasana Brkića 45',
        //     'shipping_city' => 'Sarajevo',
        //     'shipping_country' => 'BA',
        //     'shipping_phone' => '061903334',
        //     'shipping_email' => 'emir.agic@lampa.ba',
        //
        //     'sender_name' => 'Skladiše br. 1',
        //     'sender_postal_code' => '71000',
        //     'sender_address' => 'Rajlovačka cesta bb',
        //     'sender_city' => 'Sarajevo',
        //     'sender_country' => 'BA',
        //     'sender_phone' => '061903334',
        //     'sender_email' => 'emir.agic@lampa.ba',
        //
        //     'return_document' => true,
        //
        //     'remark_delivery' => '-',
        //     'remark_pickup' => '-',
        //
        //     'cod_amount' => null,
        // ];
        //
        // $shipmentData = $this->expressPost->createShipment($data);
        //
        // dump($shipmentData);
        
        // dump($this->expressPost->getShipmentStatusByShipmentId($shipmentData->CreatedShipmentId));
        //
        // if(isset($shipmentData->CreatedShipmentId))
        // {
        //   dump($this->expressPost->requestPickup($shipmentData->CreatedShipmentId));
        // }
        
    }
    
    public function test()
    {
        // $shipmentData = $this->expressPost->getShippingLabelsForSingleShipment(23741);
        $shipmentData = $this->expressPost->getShipmentStatusByShipmentId(23741);
        $shipmentData = $this->expressPost->requestPickup(23741);
        dd($shipmentData);
    }
    
    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function status($id)
    {
        $old_status = request('status');
        
        $document = $this->document->getOne($id);
        abort_if(is_null($document), 404, "Document #{$id} not found");
        
        $express_post = $document->rExpressPost;
        abort_if(is_null($express_post), 404, "Express post #{$id} not found");
        abort_if(is_null($express_post->shipment_id), 404, "Shipment id for express post #{$id} not found");
        abort_if(is_null($express_post->tracking_number_value), 404, "Tracking code for express post #{$id} not found");
    
        $traces = $this->shipmentTraces($document, $express_post);
    
        $document = $document->fresh('rStatus');
        
        $data = [
            'document' => $document,
            'traces' => (array) $traces,
            'reload' => false,
        ];
        
        if ($document->status != $old_status) {
            $data['notification'] = [
                'type' => 'success',
                'message' => 'Status pošiljke: '.$document->rStatus->name,
            ];
            $data['reload'] = true;
        }
        
        return $this->getSuccessJsonResponse($data);
    }
}
