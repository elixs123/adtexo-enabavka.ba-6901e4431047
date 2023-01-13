<?php

namespace App\Http\Requests\Billing;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class StoreBillingRequest
 *
 * @package App\Http\Requests\Billing
 */
class StoreBillingRequest extends Request
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
            'country' => 'required|string|in:ba,rs',
            'fund_source' => 'required|string',
            'kif' => 'required|string',
            'payed' => 'required|numeric',
            'date_of_payment' => 'nullable|date_format:Y-m-d',
            'person_id' => ['nullable', 'integer', Rule::exists('persons', 'id')],
        ];
    }
}
