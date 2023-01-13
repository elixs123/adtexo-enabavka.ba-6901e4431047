<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Request;
use App\Rules\UniquePhoneRule;

/**
 * Class UpdateClientRequest
 *
 * @package App\Http\Requests\Client
 */
class UpdateClientRequest extends StoreClientRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
    
        $rules['photo'] = $this->get('photo_required', 'required').'|image';
        $rules['photo_contract'] = $this->get('photo_contract_required', 'required').'|image';		
        $rules['phone'][3] = new UniquePhoneRule('clients', 'phone', $this->id);
    
        if ((int) $this->input('is_location') == 1) {
            $rules['location_code'] .= ','.$this->id.',id';
        } else {
            $rules['code'] .= ','.$this->id.',id';
        }
        
        if ($this->input('type_id') == 'private_client') {
            $rules['salesman_person_id'] = 'nullable|integer|min:1';
            $rules['routes'] = 'nullable|routes';
        }
        
        return $rules;
    }
}
