<?php

namespace App\Http\Requests\Action;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateActionProductRequest
 *
 * @package App\Http\Requests\Action
 */
class UpdateActionProductRequest extends FormRequest
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
            'action_qty' => 'required|integer|min:0',
        ];
    }
}
