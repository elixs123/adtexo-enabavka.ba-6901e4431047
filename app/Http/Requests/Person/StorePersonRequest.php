<?php

namespace App\Http\Requests\Person;

use App\Rules\UniquePhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class StorePersonRequest
 *
 * @package App\Http\Requests\Person
 */
class StorePersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $type_id = $this->get('type_id');
        
        $rules = [
            'type_id' => 'required',
            'name' => 'required|min:2|max:100',
            'email' => [
                'nullable',
                'email',
                'min:2',
                'max:100',
                Rule::unique('persons')->where(function($query) use ($type_id) {
                    return $query->where('type_id', $type_id);
                }),
            ],
            'phone' => [
                'required',
                'max:20',
                'regex:/^[0-9\+\(\)\s]+$/',
            ],
            'note' => 'max:255',
            'code' => 'nullable|max:50|unique:persons,code',
            'stock_id' => 'nullable|integer|min:1',
            'kpi_values' => 'nullable|array',
            'kpi_values.*' => 'nullable|numeric|between:0,100',
            'status' => 'required',
        ];
        
        if (($type_id == 'responsible_person') || ((int) $this->get('assign_to_user', 0) == 1)) {
            $rules['email'][0] = 'required';
        }
        
        if ($type_id == 'responsible_person') {
            $rules['phone'][] = new UniquePhoneRule('persons', 'phone', null, $type_id);
        }
        
        if ($type_id == 'focuser_person') {
            $rules['categories'] = 'array|min:0|max:100';
            $rules['products'] = 'array|min:0|max:100';
        }
        
        return $rules;
    }
}
