<?php

namespace App\Support\Scoped;

use App\Document;
use App\User;

/**
 * Class ScopedStock
 *
 * @package App\Support\Scoped
 */
class ScopedStock
{
    /**
     * @var \App\User
     */
    private $user;
    
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * @var string
     */
    private $langId = 'bs';
    
    /**
     * @var string
     */
    private $priceCountryId = 'bih';
    
    /**
     * @var integer
     */
    private $priceStockId = 2;
    
    /**
     * @var string
     */
    private $currency = 'KM';
    
    /**
     * @var int
     */
    private $taxRate = 17;
    
    /**
     * ScopedStock constructor.
     *
     * @param \App\User $user
     * @param \App\Document $document
     */
    public function __construct(User $user, Document $document = null)
    {
        $this->user = $user;
        $this->document = $document;
        
        $this->setEnvironment();
    }
    
    /**
     * @return void
     */
    private function setEnvironment()
    {
        $this->setLangId();
        
        $this->setUserEnvironment();
        
        $this->setDocumentEnvironment();
    }
    
    /**
     * @return void
     */
    private function setLangId()
    {
        $this->langId = app()->getLocale();
    }
    
    /**
     * @return void
     */
    private function setUserEnvironment()
    {
        if (!is_null($person = $this->user->rPerson) && is_null($this->document)) {
            $this->setStockEnvironment($person->rStock);
        }
    }
    
    /**
     * @return void
     */
    private function setDocumentEnvironment()
    {
        if (!is_null($this->document) && !is_null($client = $this->document->rClient)) {
            $this->langId = $client->lang_id;
            
            $this->setStockEnvironment($client->rStock);
        }
    }
    
    /**
     * @param \App\Stock|mixed $stock
     *
     * @return void
     */
    private function setStockEnvironment($stock)
    {
        if (!is_null($stock)) {
            $this->priceCountryId = $stock->country_id;
            
            $this->priceStockId = $stock->id;
            
            $this->currency = $stock->currency;
            
            $this->taxRate = $stock->tax_rate;
        }
    }
    
    /**
     * @return string
     */
    public function langId()
    {
        return $this->langId;
    }
    
    /**
     * @return string
     */
    public function priceCountryId()
    {
        return $this->priceCountryId;
    }
    
    /**
     * @return int
     */
    public function priceStockId()
    {
        return $this->priceStockId;
    }
    
    /**
     * @return string
     */
    public function currency()
    {
        return $this->currency;
    }
    
    /**
     * @return int
     */
    public function taxRate()
    {
        return $this->taxRate;
    }
}
