<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class MinPriceRule
 *
 * @package App\Rules
 */
class MinPriceRule implements Rule
{
    private $minPrice;
    
    /**
     * Create a new rule instance.
     *
     * @param float $minPrice
     */
    public function __construct($minPrice)
    {
        $this->minPrice = $minPrice;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return convert2float($value) >= $this->minPrice;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Miniminalna cijena mora biti '.format_price($this->minPrice).'.';
    }
}
