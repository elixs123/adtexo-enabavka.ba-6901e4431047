<?php namespace App\Http\Requests\Stock;

use App\Http\Requests\Request;

class UpdateStockRequest extends Request {

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
            'code' => 'required|unique:stocks,code,' . $this->input('code') . ',code|max:50',
			'status' => 'required',  
			'name' => 'required|max:100',  
			'original_name' => 'required|max:100',                    			
			'address' => 'required|max:100',                    
			'city' => 'required|max:100',                    
			'postal_code' => 'required|max:20',                    
			'country_id' => 'required|max:50'                   
		];	
	}       
}
