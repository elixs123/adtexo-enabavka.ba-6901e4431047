<?php

namespace App\Support\Scoped;

use App\Action;
use App\User;

/**
 * Class ScopedAction
 *
 * @package App\Support\Scoped
 */
class ScopedAction
{
    /**
     * @var \App\User|mixed
     */
    private $user;
    
    /**
     * @var \App\Role
     */
    private $role;
    
    /**
     * @var \App\Stock
     */
    private $stock;
    
    /**
     * @var string
     */
    private $countryId;
    
    /**
     * @var \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection
     */
    private $actions;
    
    /**
     * ScopedAction constructor.
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
        $this->role = $this->user->roles->first();
        
        $this->actions = collect([]);
        
        if (can('view-action')) {
            $this->setStock();
            
            $this->setActions();
        }
    }
    
    /**
     * @return void
     */
    private function setStock()
    {
        if ($this->user->isClient() || $this->user->isSalesAgent()) {
            if (!is_null($this->user->client)) {
                $this->stock = $this->user->client->rStock;
            }
        } else if (!is_null($person = $this->user->rPerson)) {
            $this->stock = $person->rStock;
            
        }
        
        if (!is_null($this->stock)) {
            $this->countryId = $this->stock->country_id;
        }
    }
    
    /**
     * @return void
     */
    private function setActions()
    {
        $query = $this->getQuery();
        
        $this->actions = $query->get();
    }
    
    /**
     * @return array
     */
    private function adminRoles()
    {
        return [1, 7];
    }
    
    /**
     * @return array
     */
    private function clientSalesAgentRoles()
    {
        return [2, 8];
    }
    
    /**
     * @return array
     */
    public function rolesWithAccess()
    {
        return [3, 8, 2, 4];
    }
    
    /**
     * @return \Illuminate\Database\Query\Builder|mixed
     */
    public function getQuery()
    {
        if (in_array($this->role->id, $this->adminRoles())) {
            $query = Action::query();
        } else {
            $query = $this->role->rActions();
            $query->where('stock_id', $this->stock->id);
        }
    
        if (in_array($this->role->id, $this->clientSalesAgentRoles()) && !is_null($this->user->client)) {
            $actions = $this->user->client->rActions()->get(['id'])->pluck('id')->toArray();
            
            if (count($actions) > 0) {
                $query->whereIn('id', $actions);
            }
        }
    
        $query->published(true);
    
        $query->where('status', 'active');
        
        return $query;
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getActions()
    {
        return $this->actions;
    }
    
    /**
     * @return bool
     */
    public function hasActions()
    {
        return $this->actions->count() ? true : false;
    }
    
    
    
    
    
    
}
