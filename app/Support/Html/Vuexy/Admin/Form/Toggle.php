<?php

namespace App\Support\Html\Vuexy\Admin\Form;

use Collective\Html\FormFacade;

/**
 * Trait Toggle
 *
 * @package App\Support\Html\Vuexy\Admin\Form
 */
trait Toggle
{
    /**
     * Toggle.
     *
     * @param string $name
     * @param bool $checked
     * @param array $options
     * @return \Illuminate\Support\HtmlString
     */
    public function switcher($name, $checked = null, $options = [])
    {
        // Asset
        $this->asset([
            'admin/vendor/ios7-switch/1.0.0/ios7-switch.js',
            'admin/vendor/ios7-switch/init.js',
        ]);
        
        // Class
        $class = 'switch'.(isset($options['class']) ? ' '.$options['class'] : '');
        
        // Options
        unset($options['class']);
        $options = $this->options([
            'id' => 'form-control-'.$this->getSafeName($name),
            'data-plugin-ios-switch',
        ], $options);
        
        // Input
        $checkbox = FormFacade::checkbox($name, null, $checked, $options);
        
        // Html
        $html = '<div class="ios-switch '.(old($name, $checked) ? 'on' : 'off').'"><div class="on-background background-fill"></div><div class="state-background background-fill"></div><div class="handle"></div></div>';
        
        // Element
        $element = sprintf('<div class="%s">%s%s</div>', $class, $html, $checkbox);
    
        // Return
        return $this->toHtmlString($element);
    }
}