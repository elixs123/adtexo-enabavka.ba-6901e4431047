<?php namespace App\Http\Requests\Client;

use App\Http\Requests\Request;
use App\Rules\UniquePhoneRule;
use Illuminate\Validation\Rule;

/**
 * Class StoreClientRequest
 *
 * @package App\Http\Requests\Client
 */
class StoreClientRequest extends Request {

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
	    $type = $this->get('type_id');
        
        $payment_discount = userIsSalesman() ? 15 : 100;
	    
	    $rules = [
            'parent_id' => 'nullable|integer',
            'type_id' => 'required',
            'jib' => (($type == 'private_client') ? 'nullable' : 'required').'|max:13',
            'pib' => 'nullable|max:12',
            'code' => 'nullable|min:2|max:50|unique:clients,code',
            'name' => 'required|min:2|max:100',
            'address' => 'required|min:2|max:100',
            'city' => 'required|min:2|max:100',
            'postal_code' => 'required|min:3|max:20',
            'country_id' => 'required|min:2|max:100',
            'photo' => $this->get('photo_required', 'required').'|image',
            'photo_contract' => $this->get('photo_contract_required', 'required').'|image',
            'latitude' => 'nullable|numeric|min:-90|max:90|required_with:longitude',
            'longitude' => 'nullable|numeric|min:-180|max:180|required_with:latitude',
            'phone' => [
                'required',
                'max:30',
                'regex:/^[0-9\+\(\)\s]+$/',
                new UniquePhoneRule('clients', 'phone', null),
            ],
            'status' => 'required',
            'note' => 'max:255',
            'client_person_id' => [
                'required', 'integer', 'min:1',
                Rule::unique('clients', 'client_person_id')->ignore($this->id, 'id'),
            ],
            // 'responsible_person_id' => 'required|integer|min:1',
            'responsible_person_id' => [
                'required', 'integer', 'min:1',
                Rule::unique('clients', 'responsible_person_id')->ignore($this->id, 'id'),
            ],
            'finance_person_id' => 'nullable|integer|min:1',
            'payment_person_id' => (($type == 'private_client') ? 'nullable' : 'required').'|integer|min:1',
            'supervisor_person_id' => 'nullable|integer|min:1',
            'payment_therms' => 'required',
            'payment_period' => 'required',
            'payment_type' => 'required',
            'payment_discount' => 'required|numeric|min:0|max:'.$payment_discount,
            'discount_value1' => 'required|numeric|min:0|max:100',
            'discount_value2' => 'required|numeric|min:0|max:100',
            'allowed_limit_in' => 'required|numeric|min:0',
            'allowed_limit_outside' => 'required|numeric|min:0',
            'categories' => 'array|min:0|max:100',
            'products' => 'array|min:0|max:100',
            'actions' => 'array|min:0|max:100',
            'stock_id' => 'required|integer|min:1',
            'lang_id' => 'required|min:2|max:2',
        ];
	    
	    if ((int) $this->input('is_location') == 1) {
	        $rules['code'] = 'nullable|min:2|max:50';
	        $rules += [
                'location_code' => 'nullable|min:2|max:50|unique:clients,location_code',
                'location_name' => 'required|min:2|max:100',
                'location_type_id' => (($type == 'private_client') ? 'nullable' : 'required').'|max:100',
                'category_id' => (($type == 'private_client') ? 'nullable' : 'required').'|max:100',
                'salesman_person_id' => 'required|integer|min:1',
                'routes' => 'routes',
            ];
        }
	    
		return $rules;
	}
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'routes' => trans('validation.required'),
            'client_person_id.unique' => trans('validation.unique_person'),
            'responsible_person_id.unique' => trans('validation.unique_person'),
        ];
    }
    
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return trans('client.data');
    }
}
