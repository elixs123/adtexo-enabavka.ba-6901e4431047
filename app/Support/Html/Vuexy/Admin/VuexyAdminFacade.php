<?php

namespace App\Support\Html\Vuexy\Admin;

use Illuminate\Support\Facades\Facade;

/**
 * Class VuexyAdmin
 *
 * @method static VuexyAdmin label(string $for, string $label = null, array $options = [])
 *
 * @method static VuexyAdmin input(string $type, string $name, string $value = null, array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin text(string $name, string $value = null, array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin password(string $name, array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin email(string $name, string $value = null, array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin color(string $name, string $value = null, array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin file(string $name, array $options = [], string $label = null, string $helper = null)
 *
 * @method static VuexyAdmin locked(string $name, string $value, string $placeholder, array $options = [], string $label = null)
 *
 * @method static VuexyAdmin checkbox(string $name, string $value = '', bool $checked = null, array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin radio(string $name, string $value = null, bool $checked = null, array $options = [], string $label = null, string $helper = null)
 *
 * @method static VuexyAdmin button(string $value, array $options = [])
 * @method static VuexyAdmin buttonPrimary(string $value, array $options = [])
 * @method static VuexyAdmin buttonSuccess(string $value, array $options = [])
 * @method static VuexyAdmin buttonInfo(string $value, array $options = [])
 * @method static VuexyAdmin buttonWarning(string $value, array $options = [])
 * @method static VuexyAdmin buttonDanger(string $value, array $options = [])
 *
 * @method static VuexyAdmin select(string $name, array $list = [], int|string $selected = null, array $selectAttributes = [],  array $optionsAttributes = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin selectTwo(string $name, array $list = [], int|string $selected = null, array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin selectTwoAjax(string $name, array $list = [], int|string $selected = null, array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin checkboxes(string $name, array $list = [], array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin radios(string $name, array $list = [], array $options = [], string $label = null, string $helper = null)
 *
 * @method static VuexyAdmin textarea(string $name, string $value = null, array $options = [], string $label = null, string $helper = null)
 *
 * @method static VuexyAdmin date(string $name, \Carbon\Carbon $value, array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin dateRange(string $name_start, string $name_end, \Carbon\Carbon $value_start, \Carbon\Carbon $value_end, array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin time(string $name, \Carbon\Carbon $value, array $options = [], string $label = null, string $helper = null)
 * @method static VuexyAdmin dateTime(string $name, \Carbon\Carbon $value, array $optionsDate = [], array $optionsTime = [], string $label = null, string $helper = null)
 *
 * @method static VuexyAdmin switcher(string $name, bool $checked = null, array $options = [])
 *
 * @package App\Support\Html\Vuexy\Admin
 */
class VuexyAdminFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'vuexy.admin';
    }
}
