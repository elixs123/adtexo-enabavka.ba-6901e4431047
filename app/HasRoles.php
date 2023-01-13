<?php

namespace App;

use Illuminate\Support\Facades\Auth;

trait HasRoles
{
    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Assign the given role to the user.
     *
     * @param  string $role
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $role
     * @return boolean
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Permission $permission
     * @return boolean
     */
    public function hasPermission(Permission $permission)
    {
        return $this->hasRole($permission->roles);
    }
    
    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Request $request
     * @param  String $permission
     * @param  Mixed $ability
     * @param  String $globalPermission
     * @return boolean
     */    
    public function aclOwnerCheckRun($request, $permission, $ability, $globalPermission = null)
    {
        if (!$this->aclOwnerCheck($request, $permission, $ability, $globalPermission)) {
            abort(403);
        }
        
        return true;
    }   
    
    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Request $request
     * @param  String $permission
     * @param  Mixed $ability
     * @param  String $globalPermission
     * @return boolean
     */    
    public function aclOwnerCheck($request, $permission, $ability, $globalPermission = null)
    {
        if ($request->user()->can($globalPermission)) {
            return true;
        }
        
        if($request->user()->can($permission, $ability) && $this->defineAclCheck($request->user(), $ability)) {
            return true;
        }     
        
        return false;
    }    
    
    /**
     * Determine if the user may perform the given permission.
     *
     * @param  User $user
     * @param  Mixed $ability
     * @return boolean
     */    
    public function defineAclCheck($user, $ability)
    {
        if(isset($ability->user_id) && $user->id === $ability->user_id)
        {
            return true;
        }    
        
        return false;
    }        
    
    /**
     * Determine if the user may perform the given permission.
     *
     * @param  int $userId
     * @return boolean
     */    
    
    public function isOwner($userId)
    {
        if($userId != Auth::id())
        {
            abort(403);
        }
        
        return true;
    }
}