<?php

namespace App\Support\Html\Vuexy\Admin;

use App\Support\Html\Vuexy\Admin\Form\Button;
use App\Support\Html\Vuexy\Admin\Form\Date;
use App\Support\Html\Vuexy\Admin\Form\Error;
use App\Support\Html\Vuexy\Admin\Form\Form;
use App\Support\Html\Vuexy\Admin\Form\Input;
use App\Support\Html\Vuexy\Admin\Form\Multi;
use App\Support\Html\Vuexy\Admin\Form\Other;
use App\Support\Html\Vuexy\Admin\Form\Text;
use App\Support\Html\Vuexy\Admin\Form\Toggle;
// use App\Support\Facades\Asset;
use Collective\Html\HtmlBuilder;
use Collective\Html\FormFacade;
use Illuminate\Support\HtmlString;
use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;

/**
 * Class VuexyAdmin
 *
 * @package App\Support\Html\Vuexy\Admin
 */
class VuexyAdmin
{
    use Button, Date, Error, Form, Input, Multi, Other, Text, Toggle;
    
    /**
     * The HTML builder instance.
     *
     * @var \Collective\Html\HtmlBuilder
     */
    protected $html;
    
    /**
     * VuexyAdmin constructor.
     *
     * @param \Collective\Html\HtmlBuilder $html
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(HtmlBuilder $html, Request $request)
    {
        // Html builder
        $this->html = $html;
        
        // Errors
        $this->errors = $request->session()->get('errors') ?: new ViewErrorBag();
    }
    
    /**
     * Merge default options with custom options.
     *
     * @param array $default
     * @param array $custom
     * @return array
     */
    private function options(array $default, array $custom)
    {
        return array_merge($default, $custom);
    }
    
    /**
     * Plugin: Merge default options with custom options.
     *
     * @param array $default
     * @param array $custom
     * @return string
     */
    private function pluginOptions(array $default, array $custom)
    {
        return json_encode($this->options($default, $custom));
    }
    
    /**
     * Get "safe" name for form element.
     *
     * @param string $name
     * @return string
     */
    public function getSafeName($name)
    {
        return str_replace(['[', ']'], '', $name);
    }
    
    /**
     * Transform the string to an Html serializable object
     *
     * @param $html
     * @return \Illuminate\Support\HtmlString
     */
    private function toHtmlString($html)
    {
        return new HtmlString($html);
    }
    
    /**
     * Build an HTML attribute string from an array.
     *
     * @param array $attributes
     * @return string
     */
    private function attributes($attributes)
    {
        return $this->html->attributes($attributes);
    }
    
    /**
     * Add one or more assets.
     *
     * @param string|array $asset
     * @return void
     */
    private function asset($asset)
    {
        //Asset::add($asset, 'auto', 'vendor');
    }
    
    /**
     * Is field required.
     *
     * @param array $options
     * @return bool
     */
    private function isFieldRequired(array $options)
    {
        if (isset($options['required'])) {
            return $options['required'];
        }
        
        if (in_array('required', $options)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get value attribute.
     *
     * @param string $name
     * @param null|mixed $default
     * @return |null
     */
    public function getValueAttribute($name, $default = null)
    {
        $value = FormFacade::getValueAttribute($name);
        
        return is_null($value) ? $default : $value;
    }
}
