<?php

namespace App\Support\Html\Vuexy\Admin\Form;

use Collective\Html\FormFacade;

/**
 * Trait Form
 *
 * @package App\Support\Html\Vuexy\Admin\Form
 */
trait Form
{
    /**
     * Wrap form element with "form-group".
     *
     * @param string $name
     * @param string $element
     * @param string $label
     * @param string $helper
     * @param string $required
     * @return string
     */
    private function wrapFormGroup($name, $element, $label = null, $helper = null, $required = false)
    {
        // Safe name
        $safe_name = $this->getSafeName($name);
    
        // From group: Start
        $html = sprintf('<div id="%s" class="%s" data-name="%s">', 'form-group-'.$safe_name, 'form-group'.($this->hasError($safe_name) ? $this->errorClass : '').($required ? ' required' : ''), $safe_name);
        
        // Label
        if ($label) {
            $html .= $this->label($name, $label);
        }
        
        // Element
        $html .= $element.$this->helper($helper);
    
        // Error
        if ($error = $this->error($safe_name)) {
            $html .= $error;
        }
    
        // From group: End
        $html .= '</div>';
    
        // Return
        return $html;
    }
}