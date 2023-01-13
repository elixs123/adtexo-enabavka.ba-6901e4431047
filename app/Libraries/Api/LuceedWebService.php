<?php

namespace App\Libraries\Api;

use App\Product;

/**
 * Class LuceedWebService
 *
 * @package App\Libraries\Api
 */
class LuceedWebService extends ApiRequest
{
    /**
     * @var bool
     */
    public $responseAssoc = true;
    
    /**
     * @var string
     */
    protected $apiUrl = 'http://luceedapi.tomsoft.hr:3793';
    
    /**
     * @var string
     */
    private $username = 'webshop';
    
    /**
     * @var string
     */
    private $password = 'aHYIJUo3jz';
    
    /**
     * Get headers for request.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Authorization' => 'Basic ' . base64_encode($this->username.':'.$this->password),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-CSRF-TOKEN' => csrf_token(),
            'X-Requested-With' => 'XMLHttpRequest',
        ];
    }
    
    /**
     * @param \App\Product|mixed $product
     * @param null|string $codePrefix
     * @param bool $dump
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function storeProduct($product, $codePrefix = null, $dump = false)
    {
        if (!is_null($product)) {
            $item = [
                'artikl_b2b' => $codePrefix.$product->id,
                'artikl' => $codePrefix.$product->code,
                'naziv' => $product->name,
                'porezna_tarifa' => '25',
            ];
            
            if (is_null($product->luceed_uid)) {
                $json = [
                    "artikli" => [
                        $item,
                    ],
                ];
                
                $response = $this->post('/datasnap/rest/artikli/snimi', [
                    'json' => $json,
                ]);
        
                if ($this->hasErrors()) {
                    if ($dump) {
                        dump($this->getErrors());
                    }
                    
                    return $this->getErrors();
                }
                
                if (isset($response['result'][0])) {
                    $product->update([
                        'luceed_uid' => $response['result'][0],
                    ]);
                }
            }
    
            $item['uid'] = $product->luceed_uid;
            if ($dump) {
                dump($item);
            }
            
            return $item;
        }
        
        return null;
    }
    
    /**
     * @param string $code
     * @param boolean $dump
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProductByCode($code, $dump = false)
    {
        $response = $this->get('/datasnap/rest/artikli/sifra/'.$code);
    
        if ($this->hasErrors()) {
            if ($dump) {
                dump($this->getErrors());
            }
        
            return $this->getErrors();
        }
    
        if ($dump) {
            dump($response);
        }
        
        return data_get($response, 'result.0.artikli.0', []);
    }
    
    /**
     * @param \App\Client|mixed $client
     * @param null|string $codePrefix
     * @param bool $dump
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function storeClient($client, $codePrefix = null, $dump = false)
    {
        if (!is_null($client)) {
            $item = [
                'partner_b2b' => $codePrefix.$client->id,
                'partner' => "client{$client->id}",
                'naziv' => $client->name,
                'enabled' => 'D',
                'tip_komitenta' => ($client->type_id == 'private_client') ? 'F' : 'P',
            ];
            
            if (is_null($client->luceed_uid)) {
                $json = [
                    "partner" => [
                        $item,
                    ],
                ];
                
                $response = $this->post('/datasnap/rest/partneri/snimi', [
                    'json' => $json,
                ]);
        
                if ($this->hasErrors()) {
                    if ($dump) {
                        dump($this->getErrors());
                    }
                    
                    return $this->getErrors();
                }
        
                if (isset($response['result'][0])) {
                    $client->update([
                        'luceed_uid' => $response['result'][0],
                    ]);
                }
                
                return $item;
            }
    
            $item['uid'] = $client->luceed_uid;
            if ($dump) {
                dump($item);
            }
    
            return $item;
        }
        
        return null;
    }
    
    /**
     * @param string $code
     * @param boolean $dump
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getClientByCode($code, $dump = false)
    {
        $response = $this->get('/datasnap/rest/partneri/sifra/'.$code);
        
        if ($this->hasErrors()) {
            if ($dump) {
                dump($this->getErrors());
            }
            
            return $this->getErrors();
        }
        
        if ($dump) {
            dump($response);
        }
        
        return data_get($response, 'result.0.partner.0', []);
    }
    
    /**
     * @param \App\DocumentProduct|mixed $product
     * @param null|string $codePrefix
     * @param bool $dump
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function storeDocumentProduct($product, $codePrefix = null, $dump = false)
    {
        if (!is_null($product)) {
            $item = [
                'artikl_b2b' => $codePrefix.$product->product_id,
                'artikl' => $codePrefix.$product->code,
                'naziv' => $product->name,
                'porezna_tarifa' => '25',
            ];
            
            if (is_null($product->luceed_uid)) {
                $json = [
                    "artikli" => [
                        $item,
                    ],
                ];
                
                $response = $this->post('/datasnap/rest/artikli/snimi', [
                    'json' => $json,
                ]);
                
                if ($this->hasErrors()) {
                    if ($dump) {
                        dump($this->getErrors());
                    }
                    
                    return $this->getErrors();
                }
                
                if (isset($response['result'][0])) {
                    $product->update([
                        'luceed_uid' => $response['result'][0],
                    ]);
                    
                    Product::query()->where('id', $product->product_id)->update([
                        'luceed_uid' => $response['result'][0],
                    ]);
                }
            }
            
            $item['uid'] = $product->luceed_uid;
            if ($dump) {
                dump($item);
            }
            
            return $item;
        }
        
        return null;
    }
    
    /**
     * @param \App\Document|mixed $document
     * @param null|string $codePrefix
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function storeDocument($document, $codePrefix = null, $dump = false)
    {
        if (!is_null($document)) {
            $item = [
                'nalog_prodaje_b2b' => $codePrefix.$document->id,
                'datum' => now()->format('d.m.Y'),
                'skladiste' => '10',
                'partner_uid' => null,
                'korisnik__partner_uid' => null,
                'stavke' => [],
            ];
    
            if (is_null($document->luceed_uid)) {
                $client = $this->storeClient($document->rClient, $codePrefix, $dump);
                if ($dump) {
                    dump($client);
                }
                
                if (isset($client['uid'])) {
                    $item['partner_uid'] = $client['uid'];
                    $item['korisnik__partner_uid'] = $client['uid'];
                }
                
                if (isset($item['partner_uid'], $item['korisnik__partner_uid'])) {
                    foreach ($document->rDocumentProduct as $document_product) {
                        if (is_null($document_product->luceed_uid)) {
                            $_product = $this->getProductByCode($document_product->code, $dump);
                            
                            if (isset($_product['partner_uid'])) {
                                $product = [
                                    'uid' => $_product['partner_uid'],
                                ];
                            } else {
                                $product = $this->storeDocumentProduct($document_product, $codePrefix, $dump);
                            }
                        } else {
                            $product = [
                                'uid' => $document_product->luceed_uid,
                            ];
                        }
                        if ($dump) {
                            dump($product);
                        }
                        
                        if (isset($product['uid'])) {
                            $item['stavke'][] = [
                                'artikl_uid' => $product['uid'],
                                'kolicina' => (int) $document_product->qty,
                            ];
                        }
                    }
                }
                
                if (count($item['stavke']) > 0) {
                    $json = [
                        "nalozi_prodaje" => [
                            $item,
                        ],
                    ];
    
                    $response = $this->post('/datasnap/rest/NaloziProdaje/snimi', [
                        'json' => $json,
                    ]);
    
                    if ($this->hasErrors()) {
                        if ($dump) {
                            dump($this->getErrors());
                        }
                        
                        return $this->getErrors();
                    }
    
                    if (isset($response['result'][0])) {
                        $document->update([
                            'luceed_uid' => $response['result'][0],
                        ]);
                    }
                }
            }
    
            $item['uid'] = $document->luceed_uid;
            if ($dump) {
                dump($item);
            }
            
            return $item;
        }
        
        return null;
    }
    
    /**
     * @param \App\Document|mixed $document
     * @param boolean $dump
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDocumentById($document, $dump = false)
    {
        if (!is_null($document)) {
            if (!is_null($document->luceed_uid)) {
                $response = $this->get('/datasnap/rest/NaloziProdaje/uid/'.$document->luceed_uid);
                
                if ($this->hasErrors()) {
                    if ($dump) {
                        dump($this->getErrors());
                    }
                    
                    return $this->getErrors();
                }
                
                if ($dump) {
                    dump($response);
                }
                
                return data_get($response, 'result.0.nalozi_prodaje.0', []);
            }
        }
        
        return [];
    }
}
