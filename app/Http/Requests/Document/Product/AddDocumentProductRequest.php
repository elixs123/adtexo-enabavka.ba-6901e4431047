<?php

namespace App\Http\Requests\Document\Product;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AddDocumentProductRequest
 *
 * @package App\Http\Requests\Document\Product
 */
class AddDocumentProductRequest extends FormRequest
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
            'product_id' => 'required|integer|min:1',
            'qty' => 'required|integer|min:1',
        ];
    }
}
