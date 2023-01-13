<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class MaxPriceRule
 *
 * @package App\Rules
 */
class MaxPriceRule implements Rule
{
    private $maxPrice;
    
    /**
     * Create a new rule instance.
     *
     * @param float $maxPrice
     */
    public function __construct($maxPrice)
    {
        $this->maxPrice = $maxPrice;
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
        return convert2float($value) <= $this->maxPrice;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Maksimalna iznos :attribute mora biti '.format_price($this->maxPrice, 2).'.';
    }
}
