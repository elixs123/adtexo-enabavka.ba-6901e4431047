<?php

namespace App;

use App\Support\Model\Status;

/**
 * Class Role
 *
 * @package App
 */
class Role extends BaseModel
{
	use RecordActivity, Status;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'label',
        'description',
        'status',
    ];

    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rPermissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    
    /**
     * A role can be applied to users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rUsers()
    {
        return $this->belongsToMany(User::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rActions()
    {
        return $this->belongsToMany(Action::class, 'action_roles', 'role_id', 'action_id');
    }

    /**
     * Grant the given permissions to a role.
     *
     * @param  array $permissions
     * @return mixed
     */
    public function givePermissionTo($permissions)
    {
        return  $this->rPermissions()->sync($permissions);
    }
}
