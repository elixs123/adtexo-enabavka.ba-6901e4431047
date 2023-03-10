<?php namespace App\Http\Requests\Product;

use App\Http\Requests\Request;

class StoreProductStockRequest extends Request {

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
				'qty' => 'required',  
				'stock_id' => 'required',                 					
		];
	}
}