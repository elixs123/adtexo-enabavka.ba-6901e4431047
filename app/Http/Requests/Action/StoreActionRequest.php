<?php

namespace App\Http\Requests\Action;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreActionRequest
 *
 * @package App\Http\Requests\Action
 */
class StoreActionRequest extends FormRequest
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
            'name' => 'required|max:190',
            'type_id' => 'required',
            'started_at' => 'required|date_format:Y-m-d',
            'finished_at' => 'required|date_format:Y-m-d|after:started_at',
            'roles' => 'required|array|min:1',
            'stock_id' => 'required|integer|min:1',
            'stock_type' => 'required|in:limited,unlimited',
            'qty' => 'required|integer|min:0',
            'photo' => 'nullable|image|max:4000',
            'presentation' => 'nullable|mimes:pdf|max:4000',
            'technical_sheet' => 'nullable|mimes:pdf|max:4000',
            'free_delivery' => 'nullable|integer|in:1',
            'status' => 'required',
        ];
    }
}
