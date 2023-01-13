<?php

namespace App\Http\Requests\Document;

use App\Http\Requests\Request;

/**
 * Class ChangeDocumentRequest
 *
 * @package App\Http\Requests\Document
 */
class ChangeDocumentRequest extends Request
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
        $rules = [
            'product' => 'nullable|array|min:1',
        ];
        
        if (userIsWarehouse()) {
            $rules['package_number'] = 'required|between:1,190';
            $rules['weight'] = 'nullable|between:1,190';
        }
        
        return $rules;
    }
}
