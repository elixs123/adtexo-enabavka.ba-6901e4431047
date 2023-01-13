<?php namespace App\Http\Requests\Product;

use App\Brand;
use App\Category;
use App\Http\Requests\Request;
use App\Product;
use Illuminate\Validation\Rule;

class StoreApiProductRequest extends Request {

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
		$lang_ids = ['bs', 'sr'];
        $status = get_codebook_opts('status')->pluck('code')->toArray();
        $unit_types = get_codebook_opts('unit_types')->pluck('code')->toArray();
        $brands = Brand::all(['id'])->pluck('id')->toArray();
        $categories = Category::all(['id'])->pluck('id')->toArray();

        $rules = [
            'item.code' => 'required',
            'item.status' => ['required', Rule::in($status)],
            'item.rang' => 'required|integer',
            'item.brand_id' => ['required', 'integer', Rule::in($brands)],
            'item.category_id' => ['required', 'integer', Rule::in($categories)],
            'item.unit_id' => ['required', Rule::in($unit_types)],
            'item.weight' => 'integer|nullable',
            'item.length' => 'integer|nullable',
            'item.width' => 'integer|nullable',
            'item.height' => 'integer|nullable',
            'item.loyalty_points' => 'integer|required',
            'item.is_gratis' => 'integer|required|in:0,1',
            'translation.name' => 'required|max:255',
            'translation.lang_id' => ['required', Rule::in($lang_ids)],
            'item.photo' => 'image',
        ];

        foreach (['bih', 'srb'] as $country) {
            $rules['prices.'.$country.'.mpc'] = 'required|numeric|min:0';
            $rules['prices.'.$country.'.mpc_old'] = 'required|numeric|min:0';
            $rules['prices.'.$country.'.vpc'] = 'required|numeric|min:0';
            $rules['prices.'.$country.'.vpc_old'] = 'required|numeric|min:0';
            $rules['prices.'.$country.'.mpc_eur'] = 'required|numeric|min:0';
            $rules['prices.'.$country.'.mpc_eur_old'] = 'required|numeric|min:0';
            $rules['prices.'.$country.'.vpc_eur'] = 'required|numeric|min:0';
            $rules['prices.'.$country.'.vpc_eur_old'] = 'required|numeric|min:0';
            $rules['prices.'.$country.'.mpc_discount'] = 'required|numeric|min:0';
            $rules['prices.'.$country.'.vpc_discount'] = 'required|numeric|min:0';
            $rules['prices.'.$country.'.mpc_eur_discount'] = 'required|numeric|min:0';
            $rules['prices.'.$country.'.vpc_eur_discount'] = 'required|numeric|min:0';
        }
        
        $category_id = $this->get('item')['category_id'] ?? 0;
        if (intval($category_id) == Product::PROMO_CATEGORY_ID) {
            $rules['promo'] = 'required|array|min:1';
            $rules['promo.*'] = 'required|array';
            $rules['promo.*.code'] = ['required', 'string', Rule::exists('products', 'code')];
            $rules['promo.*.qty'] = ['required', 'numeric', 'min:1'];
        }

		return $rules;
	}
}
