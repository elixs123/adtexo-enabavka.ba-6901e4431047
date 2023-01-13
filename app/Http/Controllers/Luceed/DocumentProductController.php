<?php

namespace App\Http\Controllers\Luceed;

use App\DocumentProduct;
use App\Http\Controllers\Controller;
use App\Libraries\Api\LuceedWebService;

/**
 * Class DocumentProductController
 *
 * @package App\Http\Controllers\Luceed
 */
class DocumentProductController extends Controller
{
    /**
     * @var \App\DocumentProduct
     */
    private $product;
    
    /**
     * DocumentProductController constructor.
     *
     * @param \App\DocumentProduct $product
     */
    public function __construct(DocumentProduct $product)
    {
        $this->product = $product;
    }
    
    /**
     * @param \App\Libraries\Api\LuceedWebService $apiRequest
     * @param integer $id
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(LuceedWebService $apiRequest, $id)
    {
        $product = $this->product->relation([], true)->getOne($id);
        dump($product->toArray());
        
        return $apiRequest->documentProductStore($product, 'test', true);
    }
}
