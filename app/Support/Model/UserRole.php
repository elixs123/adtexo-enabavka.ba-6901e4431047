<?php

namespace App\Support\Model;

/**
 * Trait UserRole
 *
 * @package App\Support\Model
 */
trait UserRole
{
    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->hasRole('administrator');
    }
    
    /**
     * @return mixed
     */
    public function isSalesman()
    {
        return $this->hasRole('komercijalista');
    }
    
    /**
     * @return mixed
     */
    public function isClient()
    {
        return $this->hasRole('kupac');
    }
    
    /**
     * @return mixed
     */
    public function isSupervisor()
    {
        return $this->hasRole('supervisor');
    }
    
    /**
     * @return mixed
     */
    public function isWarehouse()
    {
        return $this->hasRole('warehouse');
    }
    
    /**
     * @return mixed
     */
    public function isEditor()
    {
        return $this->hasRole('editor');
    }
    
    /**
     * @return mixed
     */
    public function isFocuser()
    {
        return $this->hasRole('fokuser');
    }
    
    /**
     * @return mixed
     */
    public function isSalesAgent()
    {
        return $this->hasRole('agent prodaje');
    }
}
