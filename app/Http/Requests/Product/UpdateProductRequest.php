<?php namespace App\Http\Requests\Product;

use App\Http\Requests\Request;

class UpdateProductRequest extends Request {

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
		$item = $this->input('item');

		return [
			'item.code' => 'required|unique:products,code,' . $item['code'] . ',code',
			'item.status' => 'required',  
			'item.unit_id' => 'required',   					 
			'item.rang' => 'required|integer',
			'item.brand_id' => 'required|integer',   					 
			'item.category_id' => 'required|integer',   
			'item.weight' => 'integer|nullable',   					 
			'item.length' => 'integer|nullable',   					 
			'item.width' => 'integer|nullable',   					 
			'item.height' => 'integer|nullable', 
			'item.loyalty_points' => 'integer',   					 
			'item.is_gratis' => 'integer',   					 
			'translation.name' => 'required|max:255',  
			'translation.lang_id' => 'required',                    
			'item.photo' => 'image',                  					
		];	
	}       
}
