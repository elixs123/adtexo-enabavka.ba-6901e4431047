<?php

namespace App;

class ProductPrice extends BaseModel
{
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    protected $casts = [
        'product_id' => 'integer',
        'mpc' => 'float',
        'mpc_old' => 'float',
        'vpc' => 'float',
        'vpc_old' => 'float',
        'mpc_eur' => 'float',
        'mpc_eur_old' => 'float',
        'vpc_eur' => 'float',
        'vpc_eur_old' => 'float',
        'badge_id' => 'integer',
        'mpc_discount' => 'float',
        'vpc_discount' => 'float',
        'mpc_eur_discount' => 'float',
        'vpc_eur_discount' => 'float'
    ];
    
    public function rProduct()
    {
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }

    public function rCountry()
    {
        return $this->belongsTo('App\CodeBook', 'country_id', 'code');
    }
    
    public function rBadge()
    {
        return $this->belongsTo('App\CodeBook', 'badge_id', 'code');
    }

	public function prepareItems($productId, $items, $convert2float = true)
	{
		$results = [];
		
		foreach($items as $countryId => $item)
		{
			$data = $item;
			$data['product_id'] = $productId;
			$data['country_id'] = $countryId;
			
			$data['mpc']              = $convert2float ? convert2float($item['mpc']) : $item['mpc'];
			$data['mpc_old']          = $convert2float ? convert2float($item['mpc_old']) : $item['mpc_old'];
			$data['vpc']              = $convert2float ? convert2float($item['vpc']) : $item['vpc'];
			$data['vpc_old']          = $convert2float ? convert2float($item['vpc_old']) : $item['vpc_old'];
			$data['mpc_eur']          = $convert2float ? convert2float($item['mpc_eur']) : $item['mpc_eur'];
			$data['mpc_eur_old']      = $convert2float ? convert2float($item['mpc_eur_old']) : $item['mpc_eur_old'];
			$data['vpc_eur']          = $convert2float ? convert2float($item['vpc_eur']) : $item['vpc_eur'];
			$data['vpc_eur_old']      = $convert2float ? convert2float($item['vpc_eur_old']) : $item['vpc_eur_old'];

            $data['mpc_discount']     = $convert2float ? convert2float($item['mpc_discount']) : $item['mpc_discount'];
            $data['vpc_discount']     = $convert2float ? convert2float($item['vpc_discount']) : $item['vpc_discount'];
            $data['mpc_eur_discount'] = $convert2float ? convert2float($item['mpc_eur_discount']) : $item['mpc_eur_discount'];
            $data['vpc_eur_discount'] = $convert2float ? convert2float($item['vpc_eur_discount']) : $item['vpc_eur_discount'];
			
			$data['badge_id'] = $item['badge_id'] ?? null;
			$data['created_at'] = now();
			
			$results[] = $data;
		}
		
		return collect($results);
	}
 
	public function insertMultiple($prodcutId, $items, $convert2float = true)
	{
		$results = $this->prepareItems($prodcutId, $items, $convert2float);

		$this->insert($results->toArray());
		
		return $results;
	}
	
	public function removeItems($prodcutId)
	{
		return self::where('product_id', $prodcutId)->delete();
	}
	
	public function syncItems($prodcutId, $items, $convert2float = true)
	{
		$this->removeItems($prodcutId);
		
		return $this->insertMultiple($prodcutId, $items, $convert2float);
	}
}
