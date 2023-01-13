<?php

namespace App\Libraries\Api;

/**
 * Class CityExpress
 *
 * @package App\Libraries\Api
 */
class CityExpress extends ExpressOne
{
    /**
     * @return string|null
     */
    public function getApiUrl()
    {
        if ($this->countryId == 'srb') {
            $this->setApiUrl('http://webapi.cityexpress.rs');
            // $this->setApiUrl('https://www.cityexpress.rs');
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
        
        if ($this->countryId == 'srb') {
            // if (config('app.env') == 'local') {
            //     return 'cbe43753-68c1-4401-a546-8c334bb17f0c';
            // }
            
            return 'cbe43753-68c1-4401-a546-8c334bb17f0c';
        }
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
        
        $center = substr($shipmentNumber, 0, 6);
        $shipment = substr($shipmentNumber, 6);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.cityexpress.rs/tracer/trackShipment?statusHistory=true&full=true&shipment={$shipment}&center={$center}&lang=sr&_=".time(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => 'UTF-8',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        
        if ($response === false) {
            throw new \Exception("Error: GetShipmentTraces :".curl_error($curl));
        }
        
        $response = str_replace('callbackJson(', '', $response);
        $response = substr($response, 0, -1);
        
        return json_decode($response, true);
    }
}
