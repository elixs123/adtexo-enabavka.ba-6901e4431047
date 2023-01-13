<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

/**
 * Class ExcelRule
 *
 * @package App\Rules
 */
class ExcelRule implements Rule
{
    /**
     * @var \Illuminate\Http\UploadedFile
     */
    private $file;
    
    /**
     * Create a new rule instance.
     *
     * @param \Illuminate\Http\UploadedFile $file
     */
    public function __construct(UploadedFile $file = null)
    {
        $this->file = $file;
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
        if (is_null($this->file)) {
            return true;
        }
        
        $extension = strtolower($this->file->getClientOriginalExtension());
    
        return in_array($extension, ['csv', 'xls', 'xlsx']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The excel file must be a file of type: csv, xls, xlsx.';
    }
}
