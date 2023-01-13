<?php

namespace App\Libraries\Api;

use Illuminate\Support\Facades\Log;

/**
 * Class PantheonApi
 *
 * @package App\Libraries\Api
 */
class PantheonApi extends ApiRequest
{
    /**
     * @var string
     */
    protected $token = '4|J8CTdUKEGzNf9oSZkbcknSmUxuggrqY7D2bXmWxg';
    
    /**
     * @var string
     */
    protected $apiUrl = 'http://65.109.108.106';
    
    /**
     * Get headers for request.
     *
     * @return array
     */
    protected function getHeaders()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ];
        
        if ($this->getToken()) {
            $headers['Authorization'] = 'Bearer ' . $this->getToken();
        }
        
        return $headers;
    }
    
    /**
     * @param array $data
     * @return array|\GuzzleHttp\Exception\RequestException|mixed|object|\Psr\Http\Message\ResponseInterface|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function insertOrder(array $data)
    {
        Log::info('PantheonApi: /api/insertorder');
        Log::info(json_encode($data, JSON_UNESCAPED_SLASHES));
        
        return $this->post('/api/insertorder', [
            'body' => json_encode($data, JSON_UNESCAPED_SLASHES),
        ]);
    }
    
    /**
     * @param string|integer $invoiceNumber
     * @param string|integer $fiscalNumber
     * @return array|\GuzzleHttp\Exception\RequestException|mixed|object|\Psr\Http\Message\ResponseInterface|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateFiscalNumber($invoiceNumber, $fiscalNumber)
    {
        return $this->post('/api/updateorder', [
            'body' => json_encode([
                'brojfakture' => $invoiceNumber,
                'rbf' => $fiscalNumber,
            ], JSON_UNESCAPED_SLASHES),
        ]);
    }
}
