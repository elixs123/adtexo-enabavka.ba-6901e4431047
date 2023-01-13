<?php

namespace App\Http\Requests\Billing;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class InsertBillingRequest
 *
 * @package App\Http\Requests\Billing
 */
class InsertBillingRequest extends Request
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
            'billings' => 'required|array|min:1',
            'billings.*' => 'required|array',
            'billings.*.country' => 'required|string|in:ba,rs',
            'billings.*.fund_source' => 'required|string',
            'billings.*.kif' => 'required|string',
            'billings.*.payed' => 'required|numeric',
            'billings.*.date_of_payment' => 'nullable|date_format:Y-m-d',
            'billings.*.person_id' => ['nullable', 'integer', Rule::exists('persons', 'id')],
        ];
    }
}
