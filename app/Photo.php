<?php

namespace App;


class Photo extends BaseModel
{
    public $timestamps = false;
    
    /**
     * Type of photos (places, texts, events...)
     * @var string
     */       
    public $module;    
    
    /**
     * Class constructor
     * @retrun void
     */     
    function __construct()
    {
    }        
    
    /**
        * Get a type of photo
        * @return string
    */    
    private function getFolder()
    {
        return $this->module;
    }
    
    /**
        * Add a photo
        * @param array $data
        * @return int
    */    
    public function addPhoto($data)
    {
        $photo = new Photo;
        $photo->name = $data['name'];
        $photo->item_id = (int) $data['item_id'];
        $photo->folder = $this->getFolder();             
        $photo->save();    
                
        return $photo->id;
    }
         
    /**
     * Get a list of photos related to item (object, text, event...)
     * @return object
    */    
    public function getPhotos($item_id)
    {
        return self::where('item_id', $item_id)->where('folder', $this->getFolder())->orderBy('id')->get();
    }
    
    /**
     * Get a data about specific photo
     * @var int $photo_id
     * @return object
    */    
    public function getPhoto($photo_id)
    {
        return self::where('id', $photo_id)->first();
    }        

    /**
     * Remove a list of photos related to item (object, text, event...)
     * @return int (affected rows)
    */    
    public function removePhotos($item_id)
    {
        return self::where('item_id', $item_id)->where('folder', $this->getFolder())->delete();
    }    

    /**
     * Remove specific photo
     * @var int $photo_id
     * @return int (affected rows)
    */     
    public function removePhoto($photo_id)
    {
        return self::destroy($photo_id);
    } 
    
    /**
     * Remove photo files form disk
     * @var int $item_id
     * @return void
    */     
    public function removePhotoFiles($item_id)
    {
        $photo = new Photo();
        $photo->module = $this->getFolder();
        $photos = $photo->getPhotos($item_id);
        
        $photos_variations = config('picture.gallery_path');        

        foreach ($photos as $key => $photo_item)
        {
            foreach ($photos_variations as $variation => $dim)
            {
                @unlink(config('picture.gallery_path') . '/' . $variation . '/' . $photo_item->name);
            }
        }
    }
}