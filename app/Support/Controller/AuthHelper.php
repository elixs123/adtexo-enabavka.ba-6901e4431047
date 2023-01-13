<?php

namespace App\Support\Controller;

use Illuminate\Support\Facades\Auth;

/**
 * Trait AuthHelper
 *
 * @package App\Support\Controller
 */
trait AuthHelper
{
    /**
     * @return \App\User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getUser()
    {
        return Auth::user();
    }
    
    /**
     * @return int|null
     */
    protected function getUserId()
    {
        return Auth::id();
    }
    
    /**
     * @param mixed $role
     * @return bool
     */
    protected function userHasRole($role)
    {
        if ($this->getUser()->hasRole($role)) {
            return true;
        }
        
        return false;
    }
}