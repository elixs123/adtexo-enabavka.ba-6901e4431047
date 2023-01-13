<?php

namespace App\Support\Model;

/**
 * Trait Log
 *
 * @package App\Support\Model
 */
trait Log
{
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function rLogs()
	{
		return $this->morphMany(Log::class, 'loggable');
	}
}
