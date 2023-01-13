<?php namespace App\Http\Requests\CodeBook;

use App\Http\Requests\Request;

class UpdateCodeBookRequest extends Request {

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
	    $rules = [
            'name' => 'required|min:2|max:200',
            'code' => 'required|min:2|max:100|unique:code_books,code,' . $this->input('code') . ',code',
            'type' => 'required',
        ];
	    
	    if ((int) $this->get('with_colors', 0) == 1) {
            $rules['background_color'] = 'required|min:7|max:8';
            $rules['color'] = 'required|min:7|max:8';
        }
	    
		return $rules;
	}
}
