<?php

namespace App;

/**
 * Class FirebaseToken
 *
 * @package App
 */
class FirebaseToken extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'firebase_tokens';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token',
        'data',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'token' => 'string',
        'data' => 'json',
    ];
    
    /**
     * @var null|integer|array
     */
    public $userId = null;
    
    /**
     * Relation: User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }
}