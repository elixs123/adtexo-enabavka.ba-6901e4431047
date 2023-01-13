<?php

namespace App\Support\Model;

/**
 * Trait DateFromToHelper
 *
 * @package App\Support\Model
 */
trait DateFromToHelper
{
    /**
     * @var string
     */
    public $dateFromToColumn = 'created_at';

    /**
     * @var string
     */
    public $dateFromValue = null;

    /**
     * @var string
     */
    public $dateToValue = null;

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesDateFromTo($query)
    {
        if (!is_null($this->dateFromValue) && $this->dateFromValue) {
            $query->where($this->dateFromToColumn, '>=', $this->dateFromValue);
        }

        if (!is_null($this->dateToValue) && $this->dateToValue) {
            $query->where($this->dateFromToColumn, '<=', $this->dateToValue);
        }

        return $query;
    }
}
