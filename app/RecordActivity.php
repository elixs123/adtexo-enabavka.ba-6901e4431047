<?php

namespace App;

use ReflectionClass;

/**
 * Trait RecordActivity
 *
 * @package App
 */
trait RecordActivity
{
    /**
     * Register the necessary event listeners.
     *
     * @return void
     */
    protected static function bootRecordActivity()
    {
        foreach (static::getModelEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }
    
    /**
     * Record activity for the model.
     *
     * @param string $event
     * @return void
     * @throws \ReflectionException
     */
    public function recordActivity($event)
    {	
		$object = $this;
		
        Activity::create([
            'item_id' => $this->id,
            'item_type' => get_class($this),
            'item_object' => json_encode($object),			
            'name' => $this->getActivityName($this, $event),
            'user_id' => auth()->id(),
        ]);
		
    }
    
    /**
     * Prepare the appropriate activity name.
     *
     * @param mixed $model
     * @param string $action
     * @return string
     * @throws \ReflectionException
     */
    protected function getActivityName($model, $action)
    {
        $name = strtolower((new ReflectionClass($model))->getShortName());

        return "{$action}_{$name}";
    }

    /**
     * Get the model events to record activity for.
     *
     * @return array
     */
    protected static function getModelEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created', 'deleted', 'updated'
        ];
    }		
}