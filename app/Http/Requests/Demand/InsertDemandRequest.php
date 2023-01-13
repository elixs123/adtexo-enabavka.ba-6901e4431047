<?php

namespace App\Http\Requests\Demand;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class InsertDemandRequest
 *
 * @package App\Http\Requests\Demand
 */
class InsertDemandRequest extends Request
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
            'demands' => 'required|array|min:1',
            'demands.*' => 'required|array',
            'demands.*.country' => 'required|string|in:ba,rs',
            'demands.*.kif' => 'required|string',
            'demands.*.binding_document' => 'nullable|string',
            'demands.*.document' => 'nullable|string',
            'demands.*.salesman_person' => 'nullable|string',
            'demands.*.client' => 'nullable|string',
            'demands.*.date_of_document' => 'required|date_format:Y-m-d',
            'demands.*.date_of_payment' => 'required|date_format:Y-m-d',
            'demands.*.amount' => 'required|numeric',
            'demands.*.payed' => 'required|numeric',
            'demands.*.debt' => 'required|numeric',
            'demands.*.overdue_days' => 'required|integer|min:0',
        ];
    }
}
