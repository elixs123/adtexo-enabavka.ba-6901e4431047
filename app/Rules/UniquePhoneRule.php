<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

/**
 * Class UniquePhoneRule
 *
 * @package App\Rules
 */
class UniquePhoneRule implements Rule
{
    /**
     * @var string
     */
    private $table;
    
    /**
     * @var string
     */
    private $column;
    
    /**
     * @var null|int
     */
    private $ignoreId;
    /**
     * @var string|null
     */
    private $typeId;
    
    /**
     * Create a new rule instance.
     *
     * @param string $table
     * @param string $column
     * @param null|int $ignoreId
     * @param string|null $typeId
     */
    public function __construct(string $table, string $column, int $ignoreId = null, string $typeId = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->ignoreId = $ignoreId;
        $this->typeId = $typeId;
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
        $value = trim($value);
        $value = preg_replace('/\s+/', '', $value);
        
        if (strlen($value) > 0) {
            $query = DB::table($this->table)->where($this->column, $value);
            
            if (is_string($this->typeId)) {
                $query->where('type_id', '=', $this->typeId);
            }
            
            if (is_numeric($this->ignoreId)) {
                $query->where('id', '<>', $this->ignoreId);
            }
            
            return $query->count() == 0;
        }
        
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.unique');
    }
}
