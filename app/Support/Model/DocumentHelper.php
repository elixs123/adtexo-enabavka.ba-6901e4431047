<?php

namespace App\Support\Model;

/**
 * Trait DocumentHelper
 *
 * @package App\Support\Model
 */
trait DocumentHelper
{
    /**
     * @var null|string|array
     */
    public $documentId = null;
    
    /**
     * Scope: Document.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopesDocument($query)
    {
        if (is_array($this->documentId)) {
            $query->whereIn('document_id', empty($this->documentId) ? [''] : $this->documentId);
        } else if (is_numeric($this->documentId) && $this->documentId) {
            $query->where('document_id', $this->documentId);
        }
    
        return $query;
    }
    
    /**
     * Relation: Document.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function rDocument()
    {
        return $this->belongsTo(\App\Document::class, 'document_id', 'id');
    }
}
