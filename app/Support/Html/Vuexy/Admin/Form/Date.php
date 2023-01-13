<?php

namespace App\Support\Html\Vuexy\Admin\Form;

use Collective\Html\FormFacade;
use Carbon\Carbon;

/**
 * Trait Date
 *
 * @package App\Support\Html\Vuexy\Admin\Form
 */
trait Date
{
    /**
     * Date.
     *
     * @param string $name
     * @param \Carbon\Carbon $value
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     * @throws \Exception
     */
    public function date($name, $value, $options = [], $label = null, $helper = null)
    {
        // Input
        $input = FormFacade::text($name, is_null($value) ? $value : $this->getDate($value)->format(trans('datetime.format.date')), [
            'id' => 'form-control-'.$this->getSafeName($name),
            'class' => 'form-control',
            'autocomplete' => 'off',
            'data-plugin-datepicker',
            'data-plugin-skin' => 'primary',
            'data-plugin-options' => $this->pluginOptions([
                'format' => trans('datetime.plugin.date'),
            ], $options),
        ]);
        
        // Element
        $element = sprintf('<div class="input-group">%s<div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div></div>', $input);
        
        // Wrap: Form group
        $wrapped = $this->wrapFormGroup($name, $element, $label, $helper, $this->isFieldRequired($options));
        
        // Return
        return $this->toHtmlString($wrapped);
    }
    
    /**
     * Date: Range.
     *
     * @param string $name_start
     * @param string $name_end
     * @param \Carbon\Carbon $value_start
     * @param \Carbon\Carbon $value_end
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function dateRange($name_start, $name_end, $value_start, $value_end, $options = [], $label = null, $helper = null)
    {
        // Input
        $input_start = FormFacade::text($name_start, is_null($value_start) ? $value_start : $this->getDate($value_start)->format(trans('datetime.format.date')), [
            'id' => 'form-control-'.$this->getSafeName($name_start),
            'autocomplete' => 'off',
            'class' => 'form-control',
        ]);
        $input_end = FormFacade::text($name_end, is_null($value_end) ? $value_end : $this->getDate($value_end)->format(trans('datetime.format.date')), [
            'id' => 'form-control-'.$this->getSafeName($name_end),
            'autocomplete' => 'off',
            'class' => 'form-control',
        ]);
        
        // Options
        $attributes = [
            'data-plugin-daterangepicker',
            'data-plugin-skin' => 'primary',
            'data-plugin-options' => $this->pluginOptions([
                'format' => trans('datetime.plugin.date'),
            ], $options),
        ];
        
        // Element
        $element = sprintf('<div class="input-daterange input-group"%s>%s<span class="input-group-addon">-</span>%s</div>', $this->attributes($attributes), $input_start, $input_end);
        $element = sprintf('<div class="input-daterange input-group"%s>%s<div class="input-group-addon"><span class="input-group-text">-</span></div>%s</div>', $this->attributes($attributes), $input_start, $input_end);
        
        // Wrap: Form group
        $wrapped = $this->wrapFormGroup($name_start.$name_end, $element, $label, $helper, $this->isFieldRequired($options));
        
        // Return
        return $this->toHtmlString($wrapped);
    }
    
    /**
     * Time.
     *
     * @param string $name
     * @param \Carbon\Carbon $value
     * @param array $options
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     * @throws \Exception
     */
    public function time($name, $value, $options = [], $label = null, $helper = null)
    {
        // Input
        $input = FormFacade::text($name, is_null($value) ? $value : $this->getDate($value)->format(trans('datetime.format.time')), [
            'id' => 'form-control-'.$this->getSafeName($name),
            'class' => 'form-control',
            'autocomplete' => 'off',
            'data-plugin-timepicker',
            'data-plugin-options' => $this->pluginOptions([
                'format' => trans('datetime.plugin.time'),
                'now' => now()->format('H:i'),
            ], $options),
        ]);
        
        // Element
        $element = sprintf('%s<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>', $input);
        
        // Wrap: Form group
        $wrapped = $this->wrapFormGroup($name, $element, $label, $helper, $this->isFieldRequired($options));
        
        // Return
        return $this->toHtmlString($wrapped);
    }
    
    /**
     * Date & Time.
     *
     * @param string $name
     * @param \Carbon\Carbon $value
     * @param array $optionsDate
     * @param array $optionsTime
     * @param string $label
     * @param string $helper
     * @return \Illuminate\Support\HtmlString
     */
    public function dateTime($name, $value, $optionsDate = [], $optionsTime = [], $label = null, $helper = null)
    {
        // Input
        $input_date = FormFacade::text($name.'_date', $this->getDate($value)->format(trans('datetime.format.date')), [
            'id' => 'form-control-'.$this->getSafeName($name.'_date'),
            'class' => 'form-control',
            'autocomplete' => 'off',
            'data-datepicker',
            'data-plugin-skin' => 'primary',
            'data-plugin-options' => $this->pluginOptions([
                'format' => trans('datetime.plugin.date'),
            ], $optionsDate),
        ]);
        $input_time = FormFacade::text($name.'_time', $this->getDate($value)->format(trans('datetime.format.time')), [
            'id' => 'form-control-'.$this->getSafeName($name.'_time'),
            'class' => 'form-control',
            'autocomplete' => 'off',
            'data-timepicker',
            'data-plugin-options' => $this->pluginOptions([
                'format' => trans('datetime.plugin.time'),
                'showMeridian' => false,
                'minuteStep' => 5,
            ], $optionsTime),
        ]);
        
        // Hidden
        $hidden = FormFacade::hidden($name, $this->getDate($value)->format(trans('datetime.plugin.full')), [
            'id' => 'form-control-'.$this->getSafeName($name),
            'data-datetimepicker',
        ]);
        
        // Element
        $element = sprintf('<span class="input-group-addon" data-plugin-datetimepicker><i class="fa fa-calendar"></i></span>%s<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>%s%s', $input_date, $input_time, $hidden);
        
        // Wrap: Form group
        $wrapped = $this->wrapFormGroup($name, $element, $label, $helper, false);
        
        // Return
        return $this->toHtmlString($wrapped);
    }
    
    /**
     * Get date.
     *
     * @param $date
     * @return \Carbon\Carbon
     * @throws \Exception
     */
    private function getDate($date)
    {
        if (!is_null($date)) {
            if ($date instanceof Carbon) {
                return $date;
            }
            
            return Carbon::createFromFormat('Y-m-d', $date);
        }
        
        return now();
    }
}
