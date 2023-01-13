<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class PriceRule
 *
 * @package App\Rules
 */
class PriceRule implements Rule
{
    /**
     * @var string
     */
    private $attribute;
    
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;
        
        $value = str_replace(['.', ','], ['', '.'], $value);
        
        return preg_match("/^\\d+\\.\\d+$/", $value) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->attribute.' nije validna.';
    }
}
