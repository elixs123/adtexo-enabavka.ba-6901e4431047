<?php namespace App\Http\Requests\Document;

use App\Http\Requests\Request;
use App\Rules\MaxPriceRule;
use App\Rules\MinPriceRule;
use App\Rules\PriceRule;
use App\Rules\User\NoDocumentInScopeRule;

/**
 * Class StoreDocumentRequest
 *
 * @package App\Http\Requests\Document
 */
class StoreDocumentRequest extends Request {

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
	    $type = $this->get('type_id', 'order');
	    $client_type = $this->get('client_type', 'private_client');
	    $country_id = $this->get('country_id', 'bih');
	    
	    $payment_discount = (($client_type == 'private_client') && ($type == 'cash')) ? config('client.global_discount_value') : 100;
	    $discount_value1 = (($type == 'cash') && userIsSalesman()) ? 24 : 50;
	    
	    $delivery_cost_max = ($country_id == 'bih') ? 10000 : 100000;
	    
		return [
			'date_of_order' => 'required|date_format:Y-m-d',
			'client_id' => 'required|integer',
			'client_type' => 'required',
			'status' => 'required',
            'type_id' => [
                'required',
                'in:'.(($client_type == 'private_client') ? 'order,cash,return' : 'preorder,order,offer,return'),
                new NoDocumentInScopeRule($this->user()),
            ],
            'payment_type' => 'required',
            'payment_period' => 'required',
            'delivery_type' => 'required',
            'payment_discount' => ['required', new PriceRule(), new MinPriceRule(0), new MaxPriceRule($payment_discount)],
            'discount_value1' => ['required', new PriceRule(), new MinPriceRule(0), new MaxPriceRule($discount_value1)],
            'delivery_cost' => ['required', new PriceRule(), new MinPriceRule(0), new MaxPriceRule($delivery_cost_max)],
            'delivery_date' => 'nullable|date_format:Y-m-d|after_or_equal:date_of_order',
		];
	}
}
