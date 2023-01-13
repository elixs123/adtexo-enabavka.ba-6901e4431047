<?php

namespace App\Http\Controllers\Luceed;

use App\Http\Controllers\Controller;
use App\Libraries\Api\LuceedWebService;
use App\Product;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers\Luceed
 */
class ProductController extends Controller
{
    /**
     * @var \App\Product
     */
    private $product;
    
    /**
     * ProductController constructor.
     *
     * @param \App\Product $product
     */
    public function __construct(Product $product)
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
    
        return $apiRequest->storeProduct($product, 'test', true);
    }
    
    /**
     * @param \App\Libraries\Api\LuceedWebService $apiRequest
     * @param string $code
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function code(LuceedWebService $apiRequest, $code)
    {
        return $apiRequest->getProductByCode($code, true);
    }
}
