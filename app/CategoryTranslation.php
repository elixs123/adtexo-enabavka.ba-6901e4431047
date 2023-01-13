<?php

namespace App;

class CategoryTranslation extends BaseModel
{
    public function rCategory()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }
	
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
        return self::where('category_id', '=', $itemId)->count();        
    }   
    
    /**
     * Edit translation
     * @param array $data
     * @return boolean
    */    
    public function editTranslation($data)
    {      		
		$item = self::where('category_id', $data['category_id'])->where('lang_id', $data['lang_id'])->first();
	
        return $item->update($data);  				
    }  
    
    /**
     * Edit slug and path after creating category
     * @param array $category
     * @param array $translation
     * @param App\Category $categoryO
     * @return void
    */     
    public function updatePathAndSlug($category, $translation, $categoryO)
    {
        if ($category->id > 0 && $category->father_id > 0)
        {                                
            $fatherInfo = $categoryO->getCatInfo($category->father_id);

            $translation['path'] = $fatherInfo->path . ' > ' . $translation['name'];
            $translation['slug'] = $fatherInfo->slug . '/' . str_slug($translation['name']);
            $translation['category_id'] = $category->id;            

            $this->editTranslation($translation);                                  
        }          
    }        
}