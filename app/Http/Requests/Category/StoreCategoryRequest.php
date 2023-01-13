<?php namespace App\Http\Requests\Category;

use App\Http\Requests\Request;

class StoreCategoryRequest extends Request {

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
			'father_id' => 'required|integer',
			'status' => 'required',
			'priority' => 'required|integer',                    
			'name' => 'required|max:100',
			'lang_id' => 'required',
			'item.photo' => 'image',                  								
		];
	}
}