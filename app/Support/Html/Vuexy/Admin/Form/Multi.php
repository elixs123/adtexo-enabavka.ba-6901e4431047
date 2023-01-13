<?php

namespace App\Support\Html\Vuexy\Admin\Form;

use Collective\Html\FormFacade;

/**
 * Trait Multi
 *
 * @package App\Support\Html\Vuexy\Admin\Form
 */
trait Multi
{
    /**
     * Select.
     *
     * @param string $name
     * @param array $list
     * @param int|string $selected
     * @param array $selectAttributes
     * @param array $optionsAttributes
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function select($name, $list = [], $selected = null, $selectAttributes = [], $optionsAttributes = [], $label = null, $helper = null)
    {
        // Options
        $selectAttributes = $this->options([
            'id' => 'form-control-'.$this->getSafeName($name),
            'class' => 'form-control',
        ], $selectAttributes);
        
        // Select
        $element = FormFacade::select($name, $list, $this->getValueAttribute($name, $selected), $selectAttributes, $optionsAttributes);
    
        // Wrap: Form group
        $wrapped = $this->wrapFormGroup($name, $element, $label, $helper, $this->isFieldRequired($selectAttributes));
    
        // Return
        return $this->toHtmlString($wrapped);
    }
    
    /**
     * Select: Plugin SelectTwo.
     *
     * @param string $name
     * @param array $list
     * @param int|string $selected
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function selectTwo($name, $list = [], $selected = null, $options = [], $label = null, $helper = null)
    {
        // Options
        $options = $this->options([
            'class' => 'form-control populate plugin-selectTwo',
            'data-plugin-selectTwo',
        ], $options);
        
        // Return
        return $this->select($name, $list, $selected, $options, [], $label, $helper);
    }
    
    /**
     * Select: Plugin SelectTwo with Ajax.
     *
     * @param string $name
     * @param array $list
     * @param int|string $selected
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function selectTwoAjax($name, $list = [], $selected = null, $options = [], $label = null, $helper = null)
    {
        // Options
        $options = $this->options([
            'class' => 'form-control populate plugin-selectTwo',
            'data-plugin-selectTwoAjax',
        ], $options);
        
        // Return
        return $this->select($name, $list, $selected, $options, [], $label, $helper);
    }
    
    /**
     * Checkboxes.
     *
     * @param string $name
     * @param array $list
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function checkboxes($name, $list = [], $options = [], $label = null, $helper = null)
    {
        // Options
        $options = $this->options([
            'id' => 'form-control-'.$this->getSafeName($name),
            'class' => 'checkboxes',
        ], $options);
        
        // Foreach: List
        $elements = '';
        foreach ($list as $value => $_label) {
            $elements .= $this->check('checkbox', $name, $value, null, [], $_label);
        }
    
        // Wrap: Checkboxes
        $html = sprintf('<div%s>%s</div>', $this->attributes($options), $elements);
    
        // Wrap
        $wrapped = $this->wrapFormGroup($name, $html, $label, $helper);
    
        // Return
        return $this->toHtmlString($wrapped);
    }
    
    /**
     * Radios.
     *
     * @param string $name
     * @param array $list
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function radios($name, $list = [], $options = [], $label = null, $helper = null)
    {
        // Options
        $options = $this->options([
            'id' => 'form-control-'.$this->getSafeName($name),
            'class' => 'radios',
        ], $options);
    
        // Foreach: List
        $elements = '';
        foreach ($list as $value => $_label) {
            $elements .= $this->check('radio', $name, $value, null, [], $_label);
        }
    
        // Wrap: Checkboxes
        $html = sprintf('<div%s>%s</div>', $this->attributes($options), $elements);
    
        // Wrap
        $wrapped = $this->wrapFormGroup($name, $html, $label, $helper);
    
        // Return
        return $this->toHtmlString($wrapped);
    }
}