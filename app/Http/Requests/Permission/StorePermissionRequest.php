<?php namespace App\Http\Requests\Permission;

use App\Http\Requests\Request;

class StorePermissionRequest extends Request {

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
                    'name' => 'required|min:2|max:100|unique:permissions',
                    'object' => 'required|max:50',                    
                    'label' => 'min:2|max:100',
                    'status' => 'required'                                                            
		];
	}

}
