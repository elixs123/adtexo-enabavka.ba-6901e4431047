<?php

namespace App;

use App\Mail\Auth\PasswordResetMail;
use App\Notifications\Auth\InviteUserNotification;
use App\Notifications\Auth\PasswordResetNotification;
use App\Support\Model\IncludeExclude;
use App\Support\Model\Search;
use App\Support\Model\Status;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Support\Model\UserRole;

/**
 * Class User
 *
 * @package App
 */
class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, HasApiTokens, Notifiable, HasRoles, RecordActivity, Search, Status, UserRole, IncludeExclude;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];
    
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'roles',
    ];
    
    /**
     * Filter user list by role
     *
     * @var string
     */
    public $roleName = null;
    
    /**
     * A user belongs to client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function rClients()
    {
        return $this->hasManyThrough(Client::class, Person::class, 'user_id', 'responsible_person_id', 'id', 'id');
    }
    
    /**
     * A user belongs to person
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function rPerson()
    {
        return $this->hasOne(Person::class)->withDefault(function ($person) {
            $person->id = 0;
            $person->name = 'Guest Author';
        });
    }
    
    /**
     * A user has many firebase tokens.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rFirebaseTokens()
    {
        return $this->hasMany(FirebaseToken::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rScopedDocument()
    {
        return $this->belongsToMany(Document::class, 'document_scope', 'user_id', 'document_id')->withTimestamps();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rContract()
    {
        return $this->hasOne(Contract::class, 'user_id', 'id');
    }
    
    /**
     * Scope a query to filter users per role
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param string|null $role
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesRole($query, $role)
    {
        if($role != null) {
            return $query->whereHas('roles', function($query) use ($role) {
                $query->where('roles.name', $role);
            });
        }
        
        return $query;
    }
    
    /**
     * Search by.
     *
     * @return string
     */
    protected function searchBy()
    {
        return 'email';
    }
    
    /**
     * Return list of items.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->sSearch()
            ->sStatus()
            ->sIncludeIds()
            ->sRole($this->roleName)
            ->orderBy('id', 'asc')
            ->sPaginate();
    }

    /**
     * Check is logged user banned.
     *
     * @return boolean
     */
    public function isBanned()
    {
        if(auth()->user()->status == 'inactive') {
            return true;
        }
        
        return false;
    }
    
    /**
     * Grant the given roles to a user.
     *
     * @param  array $roles
     * @return mixed
     */
    public function giveRoleTo($roles)
    {
        return  $this->roles()->sync($roles);
    }
    
    /**
     * @return \App\Client|\Illuminate\Database\Eloquent\Model
     */
    public function getClientAttribute()
    {
        return $this->rClients()->first();
    }
    
    /**
     * Send the password reset notification.
     *
     * @inheritdoc
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }
    
    /**
     * @param $token
     */
    public function sendInviteUserNotification($token)
    {
        $this->notify(new InviteUserNotification($token));
    }
}
