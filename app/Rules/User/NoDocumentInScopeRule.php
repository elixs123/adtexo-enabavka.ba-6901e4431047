<?php

namespace App\Rules\User;

use App\Document;
use App\User;
use Illuminate\Contracts\Validation\Rule;
use App\Support\Scoped\ScopedDocumentFacade;

/**
 * Class NoDocumentInScopeRule
 *
 * @package App\Rules\User
 */
class NoDocumentInScopeRule implements Rule
{
    /**
     * @var \App\User
     */
    private $user;
    
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * Create a new rule instance.
     *
     * @param \App\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (ScopedDocumentFacade::exist()) {
            $this->document = ScopedDocumentFacade::getDocument();
            
            return false;
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('document.errors.has_scoped', [
            'id' => $this->document->id,
            'type' => $this->document->rType->name,
        ]);
    }
}
