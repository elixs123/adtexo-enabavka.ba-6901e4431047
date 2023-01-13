<?php

namespace App\Http\Requests\Payment;

use App\Rules\ExcelRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StorePaymentRequest
 *
 * @package App\Http\Requests\Action
 */
class StorePaymentRequest extends FormRequest
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
            'type' => 'required|in:'.implode(',', config('payment.type')),
            'service' => 'required|in:'.implode(',', array_keys(config('payment.services'))),
            'file' => ['required', 'max:8000', new ExcelRule($this->file('file'))],
            'status' => 'required',
        ];
    }
}
