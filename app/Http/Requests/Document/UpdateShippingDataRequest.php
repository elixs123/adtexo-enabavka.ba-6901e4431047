<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateShippingDataRequest
 *
 * @package App\Http\Requests\Document
 */
class UpdateShippingDataRequest extends FormRequest
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
            'shipping_data.name' => 'required',
            'shipping_data.email' => 'required|email',
            'shipping_data.address' => 'required',
            'shipping_data.city' => 'required',
            'shipping_data.postal_code' => 'required',
            'shipping_data.country' => 'required',
            // 'shipping_data.phone' => 'phone',
        ];
    }
}
