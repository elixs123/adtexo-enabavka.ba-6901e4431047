<?php

namespace App\Support\Html\Vuexy\Admin\Form;

use Illuminate\Support\ViewErrorBag;

/**
 * Trait Error
 *
 * @package App\Support\Html\Vuexy\Admin\Form
 */
trait Error
{
    /**
     * Errors.
     *
     * @var \Illuminate\Support\ViewErrorBag
     */
    protected $errors = null;
    
    /**
     * Error class.
     *
     * @var string
     */
    protected $errorClass = ' is-invalid';
    
    /**
     * Parse error response.
     *
     * @param string $name
     * @return string
     */
    private function error($name)
    {
        // Check
        if ($this->hasError($name)) {
            // Return
            return '<div id="form-control-'.$name.'-error" class="invalid-feedback">'.$this->getError($name).'</div>';
        }
        
        // Return
        return '';
    }
    
    /**
     * Has error.
     *
     * @param string $name
     * @return bool
     */
    private function hasError($name)
    {
        return $this->errors->has($name);
    }
    
    /**
     * Get error
     *
     * @param string $name
     * @return string
     */
    private function getError($name)
    {
        return $this->errors->first($name);
    }
}