<?php namespace App\Http\Requests\Role;

use App\Http\Requests\Request;

class UpdateRoleRequest extends Request {

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
                    'name' => 'required|min:2|max:100|unique:roles,name,' . $this->input('name') . ',name',
                    'label' => 'required|min:2|max:100',
                    'description' => 'max:255',
                    'status' => 'required'      
		];
	}       
}
