<?php

namespace App\Http\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateContractProductRequest
 *
 * @package App\Http\Requests\Contract
 */
class UpdateContractProductRequest extends FormRequest
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
        return [];
    }
}
