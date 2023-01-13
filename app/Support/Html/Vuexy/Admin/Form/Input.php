<?php

namespace App\Support\Html\Vuexy\Admin\Form;

use Collective\Html\FormFacade;

/**
 * Trait Input
 *
 * @package App\Support\Html\Vuexy\Admin\Form
 */
trait Input
{
    /**
     * Input.
     *
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function input($type, $name, $value = null, $options = [], $label = null, $helper = null)
    {
        // Options
        $options = $this->options([
            'id' => 'form-control-'.$this->getSafeName($name),
            'class' => 'form-control',
        ], $options);
        
        // Value
        $value = $this->getValueAttribute($name, $value);
        
        // Input
        switch ($type) {
            case 'password':
                $element = FormFacade::password($name, $options);
                break;
            case 'email':
                $element = FormFacade::email($name, $value, $options);
                break;
            case 'number':
                $element = FormFacade::number($name, $value, $options);
                break;
            case 'file':
                $element = $this->customFile($name, $options);
                break;
            case 'color':
                $element = FormFacade::color($name, $value, $options);
                break;
            default:
                $element = FormFacade::text($name, $value, $options);
                break;
        }
        
        // Wrap: Form group
        $wrapped = $this->wrapFormGroup($name, $element, $label, $helper, $this->isFieldRequired($options));
        
        // Return
        return $this->toHtmlString($wrapped);
    }
    
    /**
     * Input: Text.
     *
     * @param string $name
     * @param string $value
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function text($name, $value = null, $options = [], $label = null, $helper = null)
    {
        return $this->input('text', $name, $value, $options, $label, $helper);
    }

    /**
     * Input: Number.
     *
     * @param string $name
     * @param string $value
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function number($name, $value = null, $options = [], $label = null, $helper = null)
    {
        return $this->input('number', $name, $value, $options, $label, $helper);
    }
    
    /**
     * Input: Password.
     *
     * @param string $name
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function password($name, $options = [], $label = null, $helper = null)
    {
        return $this->input('password', $name, null, $options, $label, $helper);
    }
    
    /**
     * Input: Email.
     *
     * @param string $name
     * @param string $value
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function email($name, $value = null, $options = [], $label = null, $helper = null)
    {
        return $this->input('email', $name, $value, $options, $label, $helper);
    }
    
    /**
     * Input: Number.
     *
     * @param string $name
     * @param string $value
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function color($name, $value = null, $options = [], $label = null, $helper = null)
    {
        return $this->input('color', $name, $value, $options, $label, $helper);
    }
    
    /**
     * Input: File.
     *
     * @param string $name
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function file($name, $options = [], $label = null, $helper = null)
    {
        return $this->input('file', $name, null, $options, $label, $helper);
    }
    
    /**
     * Custom file.
     *
     * @param string $name
     * @param array $options
     * @return string
     */
    private function customFile($name, $options)
    {
        // Path
        $path = isset($options['path']) ? $options['path'] : '';
        unset($options['path']);
        
        // Options
        $options = $this->options($options, [
            'name' => $name,
            'class' => 'custom-file-input',
        ]);
        
        // Element
        $element = '<input type="file" '.$this->attributes($options).'>';
        
        // Value
        $value = FormFacade::getValueAttribute($name);
        if (!(!is_null($value) && is_file(public_path($path.$value)))) {
            $value = null;
        }
        
        // Label: Browse
        $label = $this->label($name, $placeholder = is_null($value) ? (isset($options['placeholder']) ? $options['placeholder'] : trans('skeleton.choose_file')) : $value, [
            'class' => 'custom-file-label',
            'data-placeholder' => $placeholder,
        ]);
        
        // Prepend
        if (is_null($value)) {
            $prepend = '';
        } else {
            $is_image = $this->isImageFile($value);
            
            $icon = $is_image ? 'search' : 'download';
            
            $link = $is_image ? [
                'href' => asset($path.$value),
                'data-toggle' => 'magnific',
            ] : [
                'href' => asset($path.$value),
                'target' => '_blank',
            ];
            
            $prepend = '<div class="input-group-prepend btn-preview"><a class="btn btn-info" '.$this->attributes($link).'><span class="feather icon-'.$icon.'"></span></a></div>';
        }
        
        // Return
        return sprintf('<div class="input-group">%s<div class="custom-file">%s%s</div></div>', $prepend, $element, $label);
    }
    
    /**
     * Is file image file.
     *
     * @param string $filename
     * @return bool
     */
    private function isImageFile($filename)
    {
        if (in_array(strtolower(pathinfo($filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Input: Checkbox.
     *
     * @param string $name
     * @param string $value
     * @param bool $checked
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function checkbox($name, $value = '1', $checked = null, $options = [], $label = null, $helper = null)
    {
        // Html
        $element = $this->check('checkbox', $name, $value, $checked, $options, $label);
    
        // Wrap: Form group
        $wrapped = $this->wrapFormGroup($name, $element, '&nbsp;', $helper, $this->isFieldRequired($options));
    
        // Return
        return $this->toHtmlString($element);
    }
    
    /**
     * Input: Radio.
     *
     * @param string $name
     * @param string $value
     * @param bool $checked
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function radio($name, $value = null, $checked = null, $options = [], $label = null, $helper = null)
    {
        // Html
        $element = $this->check('radio', $name, $value, $checked, $options, $label);
    
        // Wrap: Form group
        $wrapped = $this->wrapFormGroup($name, $element, null, $helper, $this->isFieldRequired($options));
    
        // Return
        return $this->toHtmlString($wrapped);
    }
    
    /**
     * Input: Check (Checkbox or Radio).
     *
     * @param string $type
     * @param string $name
     * @param string $value
     * @param bool $checked
     * @param array $options
     * @param string $label
     * @return string
     */
    private function check($type, $name, $value = '1', $checked = null, $options = [], $label = null)
    {
        // Options
        $options = $this->options([
            'id' => 'form-control-'.$this->getSafeName($name.'-'.$value),
            'class' => 'custom-control-input',
        ], $options);
        
        // Element
        switch ($type) {
            case 'checkbox':
                $element = FormFacade::checkbox($name, $value, $checked, $options);
                break;
            case 'radio':
                $element = FormFacade::radio($name, $value, $checked, $options);
                break;
            default:
                $element = '<span class="text-danger">Invalid element</span>';
                break;
        }
        
        // Class
        $class = "custom-control custom-{$type} {$type}-default";
        
        // Return
        return sprintf('<div class="%s">%s%s</div>', $class, $element, $this->label($name.'-'.$value, $label, ['class' => 'custom-control-label']));
    }
    
    /**
     * Input: Price.
     *
     * @param string $name
     * @param string $value
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function masked($name, $value = null, $options = [], $label = null, $helper = null)
    {
        // Asset
        $this->asset([
            'admin/vendor/jquery.mask/1.14.15/jquery.mask.js',
            'admin/vendor/jquery.mask/init.js',
        ]);
    
        // Attributes
        $attributes = [
            'id' => 'form-control-'.$this->getSafeName($name),
            'class' => 'form-control',
            'autocomplete' => 'off',
            'data-plugin-mask',
            'data-plugin-options' => $this->pluginOptions([
                'mask' => '#,##0.00',
                'placeholder' => '0.00',
            ], $options),
        ];
        
        // Element
        $element = FormFacade::text($name, $value, $attributes);
    
        // Wrap: Form group
        $wrapped = $this->wrapFormGroup($name, $element, $label, $helper, $this->isFieldRequired($options));
    
        // Return
        return $this->toHtmlString($wrapped);
    }
}
