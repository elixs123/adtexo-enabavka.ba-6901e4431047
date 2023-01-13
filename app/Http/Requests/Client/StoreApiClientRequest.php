<?php namespace App\Http\Requests\Client;

use App\Http\Requests\Request;
use App\Rules\UniquePhoneRule;
use Illuminate\Validation\Rule;
use App\Stock;
use App\City;
/**
 * Class StoreApiClientRequest
 *
 * @package App\Http\Requests\Client
 */
class StoreApiClientRequest extends Request {

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

        $types = get_codebook_opts('client_types')->pluck('code')->toArray();
        $payment_types = get_codebook_opts('payment_type')->pluck('code')->toArray();
        $payment_periods = get_codebook_opts('payment_period')->pluck('code')->toArray();
        $payment_therms = get_codebook_opts('payment_therms')->pluck('code')->toArray();
        $lang_ids = ['bs', 'sr'];
        $status = get_codebook_opts('status')->pluck('code')->toArray();
        $countries = get_codebook_opts('countries')->pluck('code')->toArray();
        $stocks = Stock::all(['id'])->pluck('id')->toArray();
        $postal_codes = City::all(['postal_code'])->pluck('postal_code')->toArray();
        $cities = City::all(['name'])->pluck('name')->toArray();

	    $rules = [
            'parent_id' => 'nullable|integer',
            'type_id' => ['required', Rule::in($types)],
            'jib' => (($type == 'private_client') ? 'nullable' : 'required').'|max:13',
            'pib' => 'nullable|max:12',
            'code' => 'nullable|min:2|max:50',
            'name' => 'required|min:2|max:100',
            'address' => 'required|min:2|max:100',
            'city' => ['required', Rule::in($cities)],
            'postal_code' => ['required', Rule::in($postal_codes)],
            'country_id' => ['required', Rule::in($countries)],
            'latitude' => 'nullable|numeric|min:-90|max:90|required_with:longitude',
            'longitude' => 'nullable|numeric|min:-180|max:180|required_with:latitude',
            'phone' => [
                'required',
                'max:30',
                'regex:/^[0-9\+\(\)\s]+$/',
                // new UniquePhoneRule('clients', 'phone', $this->id),
            ],
            'status' => ['required', Rule::in($status)],
            'note' => 'max:255',
        
            'client_person_id' => [
                'nullable', 'integer', 'min:1',
                // Rule::unique('clients', 'client_person_id')->ignore($this->id, 'id'),
            ],
            'responsible_person_id' => [
                'nullable', 'integer', 'min:1',
                // Rule::unique('clients', 'responsible_person_id')->ignore($this->id, 'id'),
            ],
            'finance_person_id' => 'nullable|integer|min:1',
            'payment_person_id' => (($type == 'private_client') ? 'nullable' : 'nullable').'|integer|min:1',
            'supervisor_person_id' => 'nullable|integer|min:1',
            
            'payment_therms' => ['required', Rule::in($payment_therms)],
            'payment_period' => ['required', Rule::in($payment_periods)],
            'payment_type' => ['required', Rule::in($payment_types)],
            'payment_discount' => 'required|numeric|min:0|max:100',
            'discount_value1' => 'required|numeric|min:0|max:100',
            'discount_value2' => 'required|numeric|min:0|max:100',
            'stock_id' => ['required', 'integer', Rule::in($stocks)],
            'lang_id' => ['required', Rule::in($lang_ids)],
            'is_location' => ['required', Rule::in([0,1])],
        ];

	    if ((int) $this->input('is_location') == 1) {
	        $rules['code'] = 'nullable|min:2|max:50';
            //$rules['parent_id'] = 'required|integer';
            $rules += [
                'location_code' => 'nullable|min:2|max:50',
                'location_name' => 'required|min:2|max:100',
                'salesman_person_id' => 'required|integer|min:1',
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
