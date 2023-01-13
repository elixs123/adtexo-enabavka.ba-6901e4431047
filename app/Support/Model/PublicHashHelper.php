<?php

namespace App\Support\Model;

/**
 * Trait PublicHashHelper
 *
 * @package App\Support\Model
 */
trait PublicHashHelper
{
    /**
     * @return string
     */
    public function getPublicHashAttribute()
    {
        if ($this->exists) {
            return sha1($this->id.$this->created_at->timestamp);
        }
        
        return sha1('0'.now()->timestamp);
    }
    
    /**
     * @return string
     */
    public function getPublicUrlAttribute()
    {
        if ($this->exists) {
            return url("track/{$this->public_hash}/{$this->id}");
        }
        
        return url('/');
    }
    
    /**
     * @param string $hash
     * @return bool
     */
    public function isPublicHashValid($hash)
    {
        if ($this->exists) {
            return $this->public_hash === $hash;
        }
        
        return false;
    }
}
