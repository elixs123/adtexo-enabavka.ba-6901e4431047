<?php

namespace App\Http\Requests\Demand;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class StoreDemandRequest
 *
 * @package App\Http\Requests\Demand
 */
class StoreDemandRequest extends Request
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
            'kif' => 'required|string',
            'binding_document' => 'nullable|string',
            'document' => 'nullable|string',
            'salesman_person' => 'nullable|string',
            'client' => 'nullable|string',
            'date_of_document' => 'required|date_format:Y-m-d',
            'date_of_payment' => 'required|date_format:Y-m-d',
            'amount' => 'required|numeric',
            'payed' => 'required|numeric',
            'debt' => 'required|numeric',
            'overdue_days' => 'required|integer|min:0',
        ];
    }
}
