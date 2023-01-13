<?php

namespace App;

class ProductTranslation extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = []; 
    
    /**
     * Count translation
     * @param int $itemId
     * @return integer
    */     
    public function count($itemId)
    {
        return self::where('product_id', '=', $itemId)->count();        
    }   
    
    /**
     * Edit translation
     * @param array $data
     * @return boolean
    */    
    public function editTranslation($data)
    {      
        $item = self::where('product_id', $data['product_id'])->where('lang_id', $data['lang_id'])->first();
	
        return $item->update($data);   				                
    }    
    
    /**
     * Generate product link: proizvodi/title/id
     * @param string $title
     * @param int $productId
     * @return string
    */     
    public function generateLink($title, $productId, $langId)
    {            
        app()->setLocale($langId);
        
        return '/' . $langId . '/shop/' . trans('routes.product') . '/' . str_slug($title) . '/' . $productId;
    }  

    /**
     * Update link for product in database
     * @param string $title
     * @param int $productId
     * @return int
    */     
    public function updateLink($productId, $langId)
    {   
        $product = new Product();
		$data = $product->getOne($productId);
		        
        return self::where('product_id', $productId)
                    ->where('lang_id', $langId)
                    ->update(array(
                        'link' => $this->generateLink($data->name . ' ' . $data->code, $productId, $langId),
                        'search' =>  $data->code . ' ' . $data->category_path . ' ' . $data->brand_name. ' ' . $data->barcode
                    ));
    }  	
}