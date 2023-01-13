<?php namespace App\Http\Requests\Cart;

use App\Http\Requests\Request;
use App\Rules\User\NoDocumentInScopeRule;

class StoreCartRequest extends Request {

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
			'name' => 'required',
			'email' => 'required|email',
			'address' => 'required',
			'city' => 'required',
			'postal_code' => 'required',
			'country' => 'required',
			'phone' => 'phone',			
            'payment_type' => 'required'
		];
	}
}
