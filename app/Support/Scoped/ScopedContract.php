<?php

namespace App\Support\Scoped;

use App\User;

/**
 * Class ScopedContract
 *
 * @package App\Support\Scoped
 */
class ScopedContract
{
    /**
     * @var \App\User
     */
    private $user;
    
    /**
     * @var \App\Client
     */
    private $client;
    
    /**
     * @var \App\Contract
     */
    private $contract;
    
    /**
     * @var \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection
     */
    private $contractProducts;
    
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * ScopedContract constructor.
     *
     * @param \App\User|mixed $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        
        $this->setEnvironment();
    }
    
    /**
     * @return void
     */
    private function setEnvironment()
    {
        $this->contractProducts = collect([]);
        
        if ($this->user->isClient()) {
            $this->setClient();
            
            $this->setContract();
            
            $this->setProducts();
        }
    }
    
    /**
     * @param \App\Client|null $client
     * @return void
     */
    private function setClient($client = null)
    {
        $this->client = is_null($client) ? $this->user->client : $client;
    }
    
    /**
     * @return void
     */
    private function setContract()
    {
        if ($this->hasClient()) {
            $this->contract = $this->getClient()->rContract;
        }
    }
    
    /**
     * @return void
     */
    private function setProducts()
    {
        if ($this->hasContract()) {
            $this->contractProducts = $this->getContract()->rContractProducts->keyBy('product_id');
        }
    }
    
    /**
     * @return bool
     */
    public function check()
    {
        return $this->hasContract();
    }
    
    /**
     * @return bool
     */
    public function hasClient()
    {
        return !is_null($this->client);
    }
    
    /**
     * @return \App\Client|null
     */
    public function getClient()
    {
        return $this->client;
    }
    
    /**
     * @return bool
     */
    public function hasContract()
    {
        return !is_null($this->contract);
    }
    
    /**
     * @return \App\Contract|null
     */
    public function getContract()
    {
        return $this->contract;
    }
    
    /**
     * @return int|null
     */
    public function id()
    {
        if ($this->hasContract()) {
            return $this->getContract()->id;
        }
        
        return null;
    }
    
    /**
     * @return bool
     */
    public function hasProducts()
    {
        return $this->contractProducts->count() ? true : false;
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getProducts()
    {
        return $this->contractProducts;
    }
    
    /**
     * @param int $productId
     * @return bool
     */
    public function hasProduct($productId)
    {
        return $this->contractProducts->has($productId);
    }
    
    /**
     * @param int $productId
     * @return \App\ContractProduct|null
     */
    public function getProduct($productId)
    {
        return $this->contractProducts->where('product_id', $productId)->first();
    }
    
    /**
     * @param \App\Document|null $document
     */
    public function setDocument($document)
    {
        $this->document = $document;
        
        if (!is_null($this->document)) {
            $this->setClient($this->document->rClient);
        
            $this->setContract();
        
            $this->setProducts();
        }
    }
}
