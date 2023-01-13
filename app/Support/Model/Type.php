<?php

namespace App\Support\Model;

/**
 * Trait Type
 *
 * @package App\Support\Model
 */
trait Type
{
    /**
     * @var null|string|array
     */
    public $typeId = null;
    
    /**
     * Scope: Type.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesType($query)
    {
        if (is_array($this->typeId) && isset($this->typeId[0])) {
            $query->whereIn('type_id', $this->typeId);
        } else if (!is_null($this->typeId) && $this->typeId) {
            $query->where('type_id', $this->typeId);
        }
    
        return $query;
    }
    
    /**
     * Relation: Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rType()
    {
        return $this->belongsTo(\App\CodeBook::class, 'type_id', 'code');
    }
    
    /**
     * Get code book options.
     *
     * @param string $type
     * @param null|string $id
     * @return array|mixed
     */
    private function getCodeBookOptions($type, $id = null)
    {
        $types = get_codebook_opts($type)->pluck('name', 'code')->prepend('', '')->toArray();
    
        return is_null($id) ? $types : $types[$id];
    }
}