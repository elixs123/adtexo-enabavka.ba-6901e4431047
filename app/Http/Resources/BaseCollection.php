<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\ResourceResponse;
use Illuminate\Pagination\AbstractPaginator;

/**
 * Class BaseCollection
 *
 * @package App\Http\Resources
 */
class BaseCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'data';
    
    /**
     * @var bool
     */
    private $withPagination = false;
    
    /**
     * @var array
     */
    private $paginationData = [];
    
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        if ($this->resource instanceof AbstractPaginator) {
            // $this->setPaginationData()->addPaginationData();
    
            return (new ResourceResponse($this))->toResponse($request);
        }
    
        return parent::toResponse($request);
    }
    
    /**
     * Resolve the resource to an array.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return array
     */
    public function resolve($request = null)
    {
        $data = parent::resolve($request);
        
        if ($this->resource instanceof AbstractPaginator) {
            $this->setPaginationData();
            
            $data['pagination'] = $this->paginationData;
        }
        
        return $data;
    }
    
    /**
     * @return $this
     */
    private function setPaginationData()
    {
        $this->resource->appends(request()->query());
        
        $this->paginationData = [
            'total' => $this->resource->total(),
            'per_page' => $this->resource->perPage(),
            'current_page' => $this->resource->currentPage(),
            'last_page' => $this->resource->lastPage(),
    
            'from' => $this->resource->firstItem(),
            // 'path' => $this->resource->url(),
            'to' => $this->resource->lastItem(),
    
            'first_page_url' => $this->resource->url(1),
            'last_page_url' => $this->resource->url($this->resource->lastPage()),
            'prev_page_url' => $this->resource->previousPageUrl(),
            'next_page_url' => $this->resource->nextPageUrl(),
        ];
        
        return $this;
    }
    
    /**
     * @return \App\Http\Resources\BaseCollection
     */
    private function addPaginationData()
    {
        $this->additional['pagination'] = $this->paginationData;
    
        return $this;
    }
    
    /**
     * @return $this
     */
    public function withPagination()
    {
        $this->withPagination = false;
        
        return $this;
    }
}
