<?php

namespace App\Support\Html\Vuexy\Admin\Form;

use Collective\Html\FormFacade;

/**
 * Trait Other
 *
 * @package App\Support\Html\Vuexy\Admin\Form
 */
trait Other
{
    /**
     * Label.
     *
     * @param string $for
     * @param string $label
     * @param array $options
     * @return \Illuminate\Support\HtmlString|string
     */
    public function label($for, $label = null, $options = [])
    {
        // Check
        if ($label) {
            // Options
            // $options = array_merge(['class' => 'control-label'], $options);
            
            // Return
            return FormFacade::label('form-control-'.$this->getSafeName($for), $label, $options);
        }
        
        // Return
        return '';
    }
    
    /**
     * Helper.
     *
     * @param string $text
     * @return \Illuminate\Support\HtmlString|string
     */
    public function helper($text)
    {
        return ($text) ? $this->toHtmlString('<small class="text-muted">'.$text.'</small>') : '';
    }
    
    /**
     * Locked.
     *
     * @param string $name
     * @param mixed $value
     * @param mixed $placeholder
     * @param array $options
     * @param null|string $label
     * @return \Illuminate\Support\HtmlString
     */
    public function locked($name, $value, $placeholder, $options = [], $label = null)
    {
        // Options
        $options = $this->options($options, [
            'id' => 'form-control-'.$this->getSafeName($name),
            'class' => 'form-control',
        ]);
    
        // Element
        $element = '<p class="'.$options['class'].'">'.$placeholder.'</p>'.FormFacade::hidden($name, $value, $options);
    
        // Wrap: Form group
        $wrapped = $this->wrapFormGroup($name, $element, $label);
    
        // Return
        return $this->toHtmlString($wrapped);
    }
}
