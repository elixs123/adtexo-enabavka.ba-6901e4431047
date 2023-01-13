<?php namespace App\Http\Requests\Category;

use App\Http\Requests\Request;

class StoreCategoryTranslationRequest extends Request {

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
			'name' => 'required|max:100',
			'lang_id' => 'required|unique:category_translations,lang_id,category_id'
		];
	}
}