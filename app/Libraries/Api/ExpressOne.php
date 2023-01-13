<?php

namespace App\Libraries\Api;

use Illuminate\Support\Facades\Log;

/**
 * Class ExpressOne
 *
 * @package App\Libraries\Api
 */
class ExpressOne extends ApiRequest
{
    /**
     * @var string
     * @deprecated - use $this->getApiKey() instead
     */
    private $apiKey = 'aa45af7e-1cbd-4413-abab-8f67a956eefa'; #'aa520c0c-c0f9-4bb3-b1be-0073d78cc52e';
    
    /**
     * @param \App\Document|mixed $document
     * @return array|object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createShipment($document)
    {
        $data = $this->mapShipmentParams($document);
        
        $response = $this->post('/api/data/CreateShipmentPlain', ['body' => json_encode($data)]);
        
        if ($this->hasErrors()) {
            return $this->getErrors();
        }
        
        return $this->getResponseBody();
    }
    
    /**
     * @param array $data
     * @return array
     */
    public function mapShipmentParams($data)
    {
        $apiParams = array(
            'NumberOfCollies' => (int)$data['collies_number'], // required
            'Weight' => isset($data['weight']) && $data['weight'] > 0 ? $data['weight'] : null,
            'OverseasPrintsShippingLabels' => false,
            'ShipperPrintsLabels' => false,
            'Ref1' => $data['ref_1'],  // required
            'Ref2' => isset($data['ref_2']) && !empty($data['ref_2']) ? $data['ref_2'] : '-',  // required
            'Ref3' => isset($data['ref_3']) && !empty($data['ref_3']) ? $data['ref_3'] : '-',  // required
            'RemarkGoods' => null,
            'CountOfBillingUnits' => null,
            
            'ConsigneeName' => str_limit($data['shipping_name'], 35, ''),  // required
            'ConsigneePostalCode' => $data['shipping_postal_code'],   // required
            'ConsigneeStreet' => str_limit($data['shipping_address'], 70, ''),  // required
            'ConsigneeCity' => $data['shipping_city'],  // required
            'ConsigneeCountryPrefix' => $data['shipping_country'],  // required
            'ConsigneeTelephoneNumber' => isset($data['shipping_phone']) ? $data['shipping_phone'] : null,
            'ConsigneeFaxNumber' => null,
            'ConsigneeGsmNumber' => null,
            'ConsigneeEmailAddress' => isset($data['shipping_email']) ? $data['shipping_email'] : null,
            
            'SenderName' => $data['sender_name'],  // required
            'SenderPostalCode' => $data['sender_postal_code'],  // required
            'SenderStreet' => $data['sender_address'],  // required
            'SenderCity' => $data['sender_city'],  // required
            'SenderCountryPrefix' => $data['sender_country'],  // required
            'SenderTelephoneNumber' => isset($data['sender_phone']) ? $data['sender_phone'] : null,
            'SenderFaxNumber' => null,
            'SenderGsmNumber' => null,
            'SenderEmailAddress' => isset($data['sender_email']) ? $data['sender_email'] : null,
            
            'ExpressType' => 100, // required
            'ExWorksType' => null,
            'NotificationType' => 3, // required
            'AllowSaturdayDelivery' => false, // required
            
            'PickupStart' => null,
            'PickupEnd' => null,
            
            'DeliveryStart' => null,
            'DeliveryEnd' => null,
            
            'CodAmount' => $data['cod_amount'] > 0 ? (float)$data['cod_amount'] : null,
            'CodCurrency' => 0,
            'InsuranceAmount' => null,
            'Currency' => null,
            'RemarkDelivery' => $data['remark_delivery'], // required
            'RemarkPickup' => $data['remark_pickup'], // required
            'IsCargo' => false, // required
            'ReturnDocument' => $data['return_document'],
            'BillingUnit' => null,
            'ShipmentDocumentData' => null,
            'ShipmentDocumentExtension' => null,
            'ApiKey' => $this->getApiKey() // required
        );
        
        return $apiParams;
    }
    
    /**
     * @return string|null
     */
    public function getApiUrl()
    {
        if ($this->countryId == 'bih') {
            $this->setApiUrl('http://webapi.expressone.ba');
        }
        
        return $this->apiUrl;
    }
    
    /**
     * @return string
     * @throws \Exception
     */
    protected function getApiKey()
    {
        if (is_null($this->countryId)) {
            throw new \Exception("Error: API Country is null");
        }
        
        if ($this->countryId == 'bih') {
            if (config('app.env') == 'local') {
                return 'aa520c0c-c0f9-4bb3-b1be-0073d78cc52e';
            }
            
            return 'aa45af7e-1cbd-4413-abab-8f67a956eefa';
        }
    }
    
    /**
     * @param integer $shipmentId
     * @return array|object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getShippingLabelsForSingleShipment($shipmentId)
    {
        $data = ['ShipmentId' => $shipmentId, 'ApiKey' => $this->getApiKey()];
        
        $response = $this->post('/api/data/GetShippingLabelsForSingleShipment', ['body' => json_encode($data)]);
        
        if ($this->hasErrors()) {
            return $this->getErrors();
        }
        
        return $this->getResponseBody();
    }
    
    /**
     * @param array $shipmentIds
     * @return array|object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getShippingLabelsForShipments($shipmentIds)
    {
        $data = ['ShipmentIds' => $shipmentIds, 'ApiKey' => $this->getApiKey()];
        
        $response = $this->post('/api/data/GetShippingLabelsForShipments', ['body' => json_encode($data)]);
        
        if ($this->hasErrors()) {
            return $this->getErrors();
        }
        
        return $this->getResponseBody();
    }
    
    /**
     * @param int $shipmentId
     * @return array|object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getShipmentStatusByShipmentId($shipmentId)
    {
        $response = $this->get('/api/data/GetShipmentStatusByShipmentId', ['query' => ['ShipmentId' => $shipmentId, 'ApiKey' => $this->getApiKey()]]);
        
        if ($this->hasErrors()) {
            return $this->getErrors();
        }
        
        return $this->getResponseBody();
    }
	
    /**
     * @return array|object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCities()
    {
        $response = $this->get('/api/data/GetAllPickupAndDeliveryIntervals', ['query' => ['ApiKey' => $this->getApiKey()]]);
        
        if ($this->hasErrors()) {
            return $this->getErrors();
        }
        
        return $this->getResponseBody();
    }
    
    /**
     * @param string $shipmentNumber
     * @return array|object|null
     * @throws \Exception
     */
    public function getShipmentTraces($shipmentNumber)
    {
        if (is_null($this->countryId)) {
            throw new \Exception("Error: GetShipmentTraces Country is not defined");
        }
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://109.175.10.93/vip-trace-service/Service.asmx',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:car="http://www.cargonet.software/">
  <soapenv:Header>
     <car:UserCredentials>
        <!--Optional:-->
        <car:userid>Test</car:userid>
        <!--Optional:-->
        <car:password>t4t4ol!</car:password>
     </car:UserCredentials>
  </soapenv:Header>
  <soapenv:Body>
     <car:GetShipmentTrace>
        <!--Optional:-->
        <car:request>
           <car:Language>BA</car:Language>
           <!--Optional:-->
           <car:ShipmentNumber>'.$shipmentNumber.'</car:ShipmentNumber>
        </car:request>
     </car:GetShipmentTrace>
  </soapenv:Body>
</soapenv:Envelope>',
          CURLOPT_HTTPHEADER => array(
              'Content-Type: text/xml'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return $this->soapToArray($response);
    }
    
    /**
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function mapShipmentTracesParams($data)
    {
		// Map core status object
		$result = [
			'status' => null,
			'status_code' => null,
			'status_label' => null,
			'delivery_date' => null,
			'traces' => [],
			'error' => null,
		];
		
		if ($this->countryId == 'bih') {
		    $_data = $data['soapBody']['GetShipmentTraceResponse']['GetShipmentTraceResult'] ?? [];
        } else if ($this->countryId == 'srb') {
            $_data = $data;
        } else {
            throw new \Exception("Error: MapShipmentTracesParams Country is not defined");
        }
		
		if ((($this->countryId == 'bih') && (isset($_data['Traces']['Trace']))) || (($this->countryId == 'srb') && isset($_data['TraceHistory']))) {
            $tracesEvents = ($this->countryId == 'bih') ? $_data['Traces']['Trace'] : $_data['TraceHistory'];
            
            if (!isset($tracesEvents[0])) {
                $tracesEvents = [0 => $tracesEvents];
            }
            
            // Maps trace events
            foreach($tracesEvents as $traceEvent) {
                if (is_array($traceEvent) && count($traceEvent)) {
                    $result['traces'][] = [
                        'center' => $traceEvent['CenterName'] ?? '-',
                        'status_code' => (int) $traceEvent['StatusNumber'],
                        'status_label' => $traceEvent['StatusDescription'],
                        'datetime' => $traceEvent['ScanDate'] . ' ' . $traceEvent['ScanTime']
                    ];
                }
            }
            
            // Find delivered & returned event
            $deliveredEvent = collect($result['traces'])->firstWhere('status_code', 40);
            $returnedEvent = collect($result['traces'])->firstWhere('status_code', 260);
            
            // Check
            if (!is_null($deliveredEvent)) {
                $finalEvent = $deliveredEvent;
            } elseif (!is_null($returnedEvent)) {
                $finalEvent = $returnedEvent;
            } else {
                $finalEvent = $result['traces'][0] ?? null;
            }
            
            // Result
            $result['status_code'] = $finalEvent['status_code'] ?? null;
            $result['status_label'] = $finalEvent['status_label'] ?? null;
            $result['delivery_date'] = $finalEvent['datetime'] ?? null;
        } else {
            $result['status_label'] = $_data['StatusDescription'] ?? null;
            
            $result['error'] = $_data['LastError'] ?? 'Unknown Error - MapShipmentTracesParams';
        }
    
        $result['status'] = $this->mapShipmentTracesStatus($result['status_code']);
    
		return $result;
    }
    
    /**
     * @param null|int $statusCode
     * @return string
     */
    private function mapShipmentTracesStatus($statusCode)
    {
        if (is_null($statusCode)) {
            return null;
        }
        
        $statusCode = (int) $statusCode;
        
        if (in_array($statusCode, [40, 41])) {
            return 'delivered';
        }
        
        if (in_array($statusCode, [260])) {
            return 'returned';
        }
    
        return 'express_post_in_process';
    }
    
    /**
     * @param int|array $shipmentId
     * @return array|object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function requestPickup($shipmentId)
    {
        $data = [
            'ShipmentIds' => is_array($shipmentId) ? $shipmentId : [$shipmentId],
            'ApiKey' => $this->getApiKey(),
        ];
        
        $response = $this->post('/api/data/RequestPickupForShipments', ['body' => json_encode($data)]);
        
        if ($this->hasErrors()) {
            return $this->getErrors();
        }
        
        return $this->getResponseBody();
    }
    
    /**
     * @param int $shipmentId
     * @return array|object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function cancelShipment($shipmentId)
    {
        $data = [
            'ShipmentId' => (int) $shipmentId,
            'ApiKey' => $this->getApiKey(),
        ];
        
        $response = $this->post('/api/data/CancelShipment', ['body' => json_encode($data)]);
        
        if ($this->hasErrors()) {
            Log::info('cancelShipment: ' . json_encode($response));
            
            return $this->getErrors();
        }
        
        return $this->getResponseBody();
    }
    
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \GuzzleHttp\Exception\RequestException $e
     * @return array|object
     */
    protected function setErrors($response, $e)
    {
        if (is_null($response)) {
            $this->errors = [$e->getMessage()];
    
            $this->errorCode = $e->getCode();
            
            return [$e->getMessage()];
        } else {
            $content = json_decode($response->getBody()->getContents(), true);
            
            if (isset($content['IsValid']) && $content['IsValid'] == false) {
                if (is_array($content['ValidationErrors'])) {
                    foreach ($content['ValidationErrors'] as $code) {
                        $this->errors[] = $this->getValidationError($code);
                    }
                } else if (is_string($content['ValidationErrors'])) {
                    $this->errors[] = $content['ValidationErrors'];
                }
            } else {
                $this->errors = [$response->getStatusCode()];
            }
            
            $this->errorCode = $response->getStatusCode();
            
            return $content;
        }
    }
    
    /**
     * @param $shipmentData
     * @return mixed
     */
    public function getShipmentId($shipmentData)
    {
        return $shipmentData->CreatedShipmentId;
    }
    
    /**
     * @param $shipmentData
     * @return false|string|null
     */
    public function getTrackingNumber($shipmentData)
    {
        if (isset($shipmentData->Collies)) {
            if (isset($shipmentData->Collies[0])) {
                if (isset($shipmentData->Collies[0]->BarCode)) {
                    $barcode = $shipmentData->Collies[0]->BarCode;
                    
                    return substr($barcode, 0, (strlen($barcode) - 2));
                }
            }
        }
        
        return null;
    }
    
    /**
     * @param int|mixed $code
     * @return string
     */
    private function getValidationError($code)
    {
        $codes = [
            10000 => 'InvalidApiKey',
            10001 => 'PickupFromAndToDateMismatch',
            10002 => 'PickupFromMustBeLesserThanTo ',
            10003 => 'PickupNotPossibleOnDate',
            10004 => 'PickupInvalidInterval',
            10005 => 'DeliveryFromMustBeLesserThanTo ',
            10006 => 'DeliveryInvalidInterval',
            10007 => 'ShipmentNotfound ',
            10008 => 'Poštankski broj i adresa dostave se ne slažu', // ConsigneePostalCodeAndCityInvalidCombination
            10009 => 'Poštankski broj i adresa pošiljaoca se ne slažu ', // SenderPostalCodeAndCityInvalidCombination
            10010 => 'NonFailedShipmentWithRef1AndPickupDateAlreadyExists',
            10011 => 'UnsupportedBillingUnit ',
            10012 => 'CountOfBillingUnitsMustBeSet ',
            10013 => 'ExpectedSenderAddressDoesntMatch ',
            10014 => 'PickupInvalidPostalCode',
            10015 => 'DeliveryInvalidPostalCode',
            10016 => 'CutoffTimePassed ',
            10017 => 'NoActiveLabelLayoutSetting ',
            10018 => 'CannotUseOexPackaging',
            10019 => 'NoShipmentsForPickupFound',
            10020 => 'NoDataDefinedShipmentsFound',
            10021 => 'DeliveryInvalidExpressType ',
            10022 => 'InvalidExWorksType ',
            10023 => 'ExpressDeliveryNotAvailableForCargo',
            10024 => 'ColliesOrNumberOfColliesMustBeSent ',
            10025 => 'ServiceNotAvailableForSenderPostalCode ',
            10026 => 'ServiceNotAvailableForConsigneePostalCode',
            10027 => 'UnsupportedSenderCountryPrefix ',
            10028 => 'UnsupportedConsigneeCountryPrefix',
            10029 => 'BothSenderAndConsigneeCannotContainInternationalCountryPrefixes',
            10030 => 'UnsupportedFormatOfShipmentDocument',
            10031 => 'UnsupportedFormatOfColliDocument ',
            10032 => 'NoPostalLocationsFound ',
            10033 => 'CannotUpdateShipment ',
            10034 => 'PickupNotYetRequestedForShipment ',
            10035 => 'PickupNotYetRequestedForColli',
            10036 => 'UnsupportedSearchByRefType ',
            10037 => 'InvalidIBAN',
            10038 => 'ColliWithCustomerBarcodeAndPickupDateAlreadyExists ',
            10039 => 'PickupListDoesNotExist ',
            10040 => 'ApiKeyCannotManuallySetRef3',
            10041 => 'ApiKeyUsesAutomaticInitWeightMustBeSet ',
            10500 => 'InvalidExpressType ',
            11000 => 'ApiKeyNotAuthorizedToRequestEuroplate',
            11001 => 'ShipmentNotValidForEuroplatePrinting ',
            11002 => 'InvalidShipmentStatusForEuroplatePrinting',
            11003 => 'ColliWeightIsGreaterThanMaxAllowedColliWeight',
            11004 => 'InsufficientConsigneeData',
            11005 => 'ColliWeightIsZero',
            15000 => 'ApiKeyNotAuthorizedToAdministrateApiKeys ',
            15001 => 'PostalCodeAndCityInvalidCombination',
            15002 => 'InvalidLogoImageData ',
            15003 => 'ApiKeyNotFound ',
            15004 => 'CannotRemoveAdministrationPrivileges ',
            16000 => 'ApiKeyCannotUseShipmentsWithReturns',
            16001 => 'CannotUseParcelShopsIfShipmentContainsPickCollies',
            16002 => 'ExpectedReturnShipmentConsigneeDoesntMatch ',
            16003 => 'PickupAndDeliveryDatesMustBeNullWhenCreatingShipmentWithReturn ',
            16004 => 'ShipmentWithReturnCannotUseCashOnDeliveryService ',
            16005 => 'NoActiveLabelLayoutSettingForReturnShipment',
            16006 => 'FetchingLabelForColliInReturnShipmentIsNotAllowed',
            16007 => 'ColliInReturnShipmentCannotBeMarkedForNoPrint',
            16008 => 'CannotGetPickupListForReturnShipmentColli',
            16009 => 'EndpointDoesNotSupportShipmentsWithReturns ',
            16010 => 'ReturnShipmentsCannotBeCancelledDirectly ',
            20000 => 'UnknownError ',
            20001 => 'ApiKeyFailedToSerialize',
            20002 => 'SystemMaintenance',
        ];
        
        return $codes[(int)$code] ?? "Unknown Error {$code}";
    }
}
