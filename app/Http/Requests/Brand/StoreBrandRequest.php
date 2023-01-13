<?php namespace App\Http\Requests\Brand;

use App\Http\Requests\Request;

class StoreBrandRequest extends Request {

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
					'status' => 'required',  
                    'priority' => 'required|integer',   					 
                    'name' => 'required|max:100|unique:brands',                    
                    'photo' => 'image'                  					
		];
	}
}