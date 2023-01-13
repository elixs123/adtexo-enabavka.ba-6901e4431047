<?php

namespace App\Support\Html\Vuexy\Admin\Form;

use Collective\Html\FormFacade;

/**
 * Trait Button
 *
 * @package App\Support\Html\Vuexy\Admin\Form
 */
trait Button
{
    /**
     * Button.
     *
     * @param string $value
     * @param array $options
     * @return \Illuminate\Support\HtmlString
     */
    public function button($value, $options = [])
    {
        // Class
        if (!array_key_exists('class', $options)) {
            $options['class'] = 'btn btn-default';
        }
    
        // Return
        return FormFacade::button($value, $options);
    }
    
    /**
     * Button: Primary.
     *
     * @param string $value
     * @param array $options
     * @return \Illuminate\Support\HtmlString
     */
    public function buttonPrimary($value, $options = [])
    {
        return $this->button($value, $this->setButtonClass($options, 'btn btn-primary'));
    }
    
    /**
     * Button: Success.
     *
     * @param string $value
     * @param array $options
     * @return \Illuminate\Support\HtmlString
     */
    public function buttonSuccess($value, $options = [])
    {
        return $this->button($value, $this->setButtonClass($options, 'btn btn-success'));
    }
    
    /**
     * Button: Info.
     *
     * @param string $value
     * @param array $options
     * @return \Illuminate\Support\HtmlString
     */
    public function buttonInfo($value, $options = [])
    {
        return $this->button($value, $this->setButtonClass($options, 'btn btn-info'));
    }
    
    /**
     * Button: Warning.
     *
     * @param string $value
     * @param array $options
     * @return \Illuminate\Support\HtmlString
     */
    public function buttonWarning($value, $options = [])
    {
        return $this->button($value, $this->setButtonClass($options, 'btn btn-warning'));
    }
    
    /**
     * Button: Danger.
     *
     * @param string $value
     * @param array $options
     * @return \Illuminate\Support\HtmlString
     */
    public function buttonDanger($value, $options = [])
    {
        return $this->button($value, $this->setButtonClass($options, 'btn btn-danger'));
    }
    
    /**
     * Set button class.
     *
     * @param array $options
     * @param string $default
     * @return array
     */
    private function setButtonClass($options, $default)
    {
        // Check
        if (array_key_exists('class', $options)) {
            $options['class'] = $default.' '.$options['class'];
        } else {
            $options['class'] = $default;
        }
        
        // Return
        return $options;
    }
}