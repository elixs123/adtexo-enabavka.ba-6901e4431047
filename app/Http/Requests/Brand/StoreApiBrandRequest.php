<?php namespace App\Http\Requests\Brand;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class StoreApiBrandRequest extends Request {

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
        $status = get_codebook_opts('status')->pluck('code')->toArray();

		return [
            'status' => ['required', Rule::in($status)],
                    'priority' => 'required|integer',   					 
                    'name' => 'required|max:100',
                    'photo' => 'image'                  					
		];
	}
}