<?php

namespace App\Support\Html\Vuexy\Admin\Form;

use Collective\Html\FormFacade;

/**
 * Trait Text
 *
 * @package App\Support\Html\Vuexy\Admin\Form
 */
trait Text
{
    /**
     * Textarea.
     *
     * @param string $name
     * @param string $value
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function textarea($name, $value = null, $options = [], $label = null, $helper = null)
    {
        // Options
        $options = $this->options([
            'id' => 'form-control-'.$this->getSafeName($name),
            'class' => 'form-control',
            'rows' => 5,
        ], $options);
    
        // Value
        $value = $this->getValueAttribute($name, $value);
        
        // Textarea
        $element = FormFacade::textarea($name, $value, $options);
    
        // Wrap: Form group
        $wrapped = $this->wrapFormGroup($name, $element, $label, $helper, $this->isFieldRequired($options));
    
        // Return
        return $this->toHtmlString($wrapped);
    }
}