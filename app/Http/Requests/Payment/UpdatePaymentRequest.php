<?php

namespace App\Http\Requests\Payment;

/**
 * Class UpdatePaymentRequest
 *
 * @package App\Http\Requests\Payment
 */
class UpdatePaymentRequest extends StorePaymentRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
    
        $rules['file'][0] = 'nullable';
        
        return $rules;
    }
}
