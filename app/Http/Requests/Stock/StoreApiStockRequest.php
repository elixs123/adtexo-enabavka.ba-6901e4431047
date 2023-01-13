<?php namespace App\Http\Requests\Stock;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class StoreApiStockRequest extends Request {

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
        $status = get_codebook_opts('status')->pluck('code')->toArray();
        $country_ids = ['bih', 'srb'];
        $currency = get_codebook_opts('currency')->pluck('code')->toArray();

        return [
			'code' => 'required',
            'status' => ['required', Rule::in($status)],
            'name' => 'required|max:100',
			'original_name' => 'required|max:100',                    
			'email' => 'required|email|max:100',                    
			'phone' => 'required|max:20',                    			
			'address' => 'required|max:100',                    
			'city' => 'required|max:100',                    
			'postal_code' => 'required|max:20',
            'tax_rate' => 'required|integer',
            'currency' => ['required', Rule::in($currency)],
            'country_id' => ['required', Rule::in($country_ids)],
		];
	}
}