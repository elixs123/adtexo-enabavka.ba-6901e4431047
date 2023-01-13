<?php

namespace App\Http\Requests\Person;

use App\Rules\UniquePhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdatePersonRequest
 *
 * @package App\Http\Requests\Person
 */
class UpdatePersonRequest extends StorePersonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $type_id = $this->get('type_id');
        
        $rules = parent::rules();
    
        $rules['code'] .= ','.$this->id.',id';
        
        $rules['email'][4] = Rule::unique('persons')->ignore($this->id, 'id')->where(function($query) use ($type_id) {
            return $query->where('type_id', $type_id);
        });
    
        if ($type_id == 'responsible_person') {
            $rules['phone'][3] = new UniquePhoneRule('persons', 'phone', $this->id, $type_id);
        }
        
        return $rules;
    }
}
