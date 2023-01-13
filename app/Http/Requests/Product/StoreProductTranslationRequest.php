<?php namespace App\Http\Requests\Product;

use App\Http\Requests\Request;

class StoreProductTranslationRequest extends Request {

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
			'translation.name' => 'required|max:100',
			'translation.lang_id' => 'required|unique:product_translations,lang_id,NULL,id,product_id,' . $this->get('translation')['product_id'],          
			'translation.product_id' => 'required|integer',
		];
	}
}