<?php namespace App\Http\Requests\Document;

use App\Http\Requests\Request;

class UpdateDocumentRequest extends Request {

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
			'client_id' => 'required|integer',
			'status' => 'required',                 					
		];	
	}       
}
