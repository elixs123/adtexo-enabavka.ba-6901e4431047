<?php

namespace App\Libraries\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Cookie;
use GuzzleHttp\Exception\RequestException;

/**
 * Class ApiRequest
 *
 * @package App\Libraries\Fantasy
 */
class ApiRequest
{
    /**
     * @var bool
     */
    protected $debug = false;
    
    /**
     * @var array
     */
    protected $errors = [];
    
    /**
     * @var null|int
     */
    protected $errorCode = null;
    
    /**
     * @var null|mixed|\Psr\Http\Message\ResponseInterface
     */
    protected $response = null;
    
    /**
     * @var null|array|object
     */
    protected $responseBody = null;
    
    /**
     * @var bool
     */
    public $responseAssoc = false;
	
    /**
     * @var string
     */
    protected $token = null;
	
    /**
     * @var string
     */
    protected $apiUrl = null;
    
    /**
     * @var null|string
     */
    public $countryId = null;
    
    /**
     * Make request.
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function make($method, $uri, $options = [])
    {
        try {
            $this->response = $this->getClient()->request(strtoupper($method), $uri, $this->getOptions($options));
        } catch (RequestException $e) {
            \Log::error("{$method} {$uri} - ApiRequest RequestException error: " . $e->getMessage());
            if (is_null($e->getResponse())) {
                $this->errors = [$e->getMessage()];
                $this->errorCode = $e->getCode();
    
                return $this->errors;
            }
            
           return $this->setErrors($e->getResponse(), $e);
        } catch (\Exception $e) {
            \Log::error("{$method} {$uri} - ApiRequest Exception error: " . $e->getMessage());
            $this->errors = [$e->getMessage()];
            $this->errorCode = $e->getCode();
            
            return $this->errors;
        }
    
        return ($this->responseBody = json_decode($this->response->getBody(), $this->responseAssoc));
    }
    
    /**
     * Request: GET.
     *
     * @param string $uri
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($uri, $options = [])
    {
        return $this->make('get', $uri, $options);
    }
    
    /**
     * Request: POST.
     *
     * @param string $uri
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($uri, $options = [])
    {
        return $this->make('post', $uri, $options);
    }
    
    /**
     * Request: PUT.
     *
     * @param string $uri
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put($uri, $options = [])
    {
        $options = $this->setFormMethod($options, 'PUT');
        
        return $this->make('post', $uri, $options);
    }
    
    /**
     * Request: DELETE.
     *
     * @param string $uri
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($uri, $options = [])
    {
        $options = $this->setFormMethod($options, 'DELETE');
        
        return $this->make('post', $uri, $options);
    }
    
    /**
     * Get client.
     *
     * @return \GuzzleHttp\Client|null
     * @throws \Exception
     */
    public function getClient()
    {
        $base_uri = $this->getApiUrl();
    
        if (is_null($base_uri)) {
            throw new \Exception("Error: API URL is null");
        }
        
        return new Client([
            'base_uri' => $base_uri,
            'query' => [],
            'timeout' => 15,
			'verify' => false,
            'cookies' => true
        ]);
    }
    
    /**
     * Merge default options with custom options.
     *
     * @param array $options
     * @param string $method
     * @return array
     */
    private function setFormMethod(array $options, $method)
    {
        if (isset($options['query'])) {
            $options['query']['_method'] = strtoupper($method);
        } else {
            $options['query'] = [
                '_method' => strtoupper($method),
            ];
        }
        
        return $options;
    }
    
    /**
     * Get request options.
     *
     * @param array $options
     * @return array
     */
    protected function getOptions($options)
    {
        return array_merge([
            'headers' => $this->getHeaders(),
            //'cookies' => $this->getCookies(),
            'debug' => $this->debug,
        ], $options);
    }
	
    /**
     * Set token
     *
     * @return string
     */
    public function setToken($token)
	{
		$this->token = $token;
	
		return $this->token;
    }

	/**
     * Get token
     *
     * @return string
     */
    public function getToken()
	{
        return $this->token;
    }
    
    /**
     * Get headers for request.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
			'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-CSRF-TOKEN' => csrf_token(),
            'X-Requested-With' => 'XMLHttpRequest',
        ];
    }
    
    /**
     * Get cookies for request.
     *
     * @return \GuzzleHttp\Cookie\CookieJar
     */
    private function getCookies()
    {
        return CookieJar::fromArray([
            'laravel_token' => encrypt(Cookie::get('laravel_token')),
        ], config('services.api.cookie.domain'));
    }
    
    /**
     * @return bool
     */
    public function hasErrors()
    {
        if(empty($this->errors))
        {
            return false;
        }

        return true;
    }
    
    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * @return null|int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
    
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \GuzzleHttp\Exception\RequestException $e
     * @return array|object
     */
    protected function setErrors($response, $e)
    {
        if (is_null($response)) {
            $this->errors[] = $e->getMessage();
            
            return $e;
        }
        
        $content = json_decode($response->getBody()->getContents(), true);

        if(isset($content['errors']))
        {
            $this->errors = $content['errors'];
        }
        elseif(isset($content['message']))
        {
             $this->errors = [$content['message']];
        }
        elseif(isset($content['error']))
        {
            $this->errors = [$content['error']];
        }
        else {
             $this->errors = [$response->getStatusCode()];
        }

        $this->errorCode = $response->getStatusCode();
        
        return $content;
    }
    
    /**
     * @return array|object|null
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }
    
    /**
     * Set debug options.
     *
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = (bool) $debug;
    }
    
    /**
     * @return string|null
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }
	
    /**
     * Set token
     *
     * @return string
     */
    public function setApiUrl($apiUrl)
	{
		$this->apiUrl = $apiUrl;

		return $this->apiUrl;
    }
	
    /**
     * Convert SOAP to Array
     * @param string $soap
     * @return arrray
     */
	public function soapToArray($soap)
	{
	   $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", '$1$2$3', $soap);
	   $xml = simplexml_load_string($xml);
	   $json = json_encode($xml);
	   
	   return json_decode($json,true);
	}
}
