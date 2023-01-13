<?php

namespace App\Http\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreContractRequest
 *
 * @package App\Http\Requests\Contract
 */
class StoreContractRequest extends FormRequest
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
        return [
            'client_id' => 'required|integer|unique:contracts|min:1',
            'note' => 'nullable|max:255',
            'status' => 'required',
        ];
    }
}
