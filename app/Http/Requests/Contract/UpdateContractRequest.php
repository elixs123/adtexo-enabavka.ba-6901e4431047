<?php

namespace App\Http\Requests\Contract;

/**
 * Class UpdateContractRequest
 *
 * @package App\Http\Requests\Contract
 */
class UpdateContractRequest extends StoreContractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        
        $rules['client_id'] = 'required|integer|unique:contracts,id,' . $this->id . ',id|min:1';
        
        return $rules;
    }
}
