<?php

namespace App;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

/**
 * Trait PhotoHelper
 *
 * @package App
 */
trait PhotoHelper
{
    public $removeTimestampFromName = false;
	
    public $disableOptimize = true;
    
    /**
     * Upload photo on server storage and return photo name
     *
     * @param string $field
     * @param string $fileName
     * @param string $path
     * @param Request|mixed $request
     * @return mixed
     */
    public function upload($field, $fileName, $path, $request)
    {
        if ($request->hasFile($field))
        {
            $file = $request->file($field);
			
            $fileNameNew = $this->removeTimestampFromName == false ? $this->preparePhotoName($fileName, $file->getClientOriginalExtension()) : $fileName.'.'.$file->getClientOriginalExtension();
            //
            // if (!File::isDirectory(public_path($path . '/original'))) {
            //     File::makeDirectory(public_path($path . '/original'));
            // }

            $file->move(public_path($path . '/original'), $fileNameNew);

			if($this->disableOptimize == false)
			{
				$this->optimize(public_path($path . '/original/' . $fileNameNew));
			}
						
            return $fileNameNew;
        }
        
        return null;
    }
    
    /**
     * Prepare nice and unique file name for photos
     *
     * @param string $realName
     * @param string $ext
     * @param string $suffix
     * @return string
    */
    public function preparePhotoName($realName, $ext = 'jpg', $suffix = '')
    {
        return time() . '-' . str_slug($realName) . $suffix . '.' . $ext;
    }
    
    /**
     * Make smaller version of photo
     *
     * @var string $photo_path
     * @return void
    */
    public function makePhotoVarations($path, $filename, $photoVariations, $watermark = 0)
    {
        foreach ($photoVariations as $variation => $dim)
        {
            if($dim['w'] != null && $dim['h'] != null)
            {
                if (!File::isDirectory(public_path($path . '/' . $variation))) {
                    File::makeDirectory(public_path($path . '/' . $variation));
                }

                Image::make(public_path($path . '/original/' . $filename))
                        ->fit($dim['w'], $dim['h'], function ($constraint) { $constraint->upsize(); })
                        ->save(public_path($path . '/' . $variation . '/' . basename($filename)));
				
				if($this->disableOptimize == false)
				{
					$this->optimize(public_path($path . '/' . $variation . '/' . basename($filename)));
				}
            }
        }
    }
    
    /**
     * Make smaller version of photo
     *
     * @var string $path
     * @var string $filename
     * @var array $photoVariations
     * @var int $watermark
     * @return void
    */
    public function makePhotoThumbs($path, $filename, $photoVariations, $watermark = 0)
    {
        if (is_null($filename)) {
            return;
        }
        
        foreach ($photoVariations as $variation => $dim)
        {
			Image::make(public_path($path . '/original/' . $filename))
					->fit($dim['w'], $dim['h'], function ($constraint) { $constraint->upsize(); })
					->save(public_path($path . '/' . $variation . '_' . $filename));
			  
			if($variation == 'big' && $watermark == 1)
			{
				$this->watermark($path . '/' . $variation . '_' . $filename);
			}
			
			if($this->disableOptimize == false)
			{
				$this->optimize(public_path($path . '/' . $variation . '_' . $filename));
			}
        }
        
        //@unlink(public_path($path . '/' . $filename));
    }
    
    /**
     * Put watermark
     *
     * @var string $path
     * @return void
     */
    public function watermark($path) {
        
        Image::make(public_path($path))
                ->insert(public_path('assets/img/watermark.png'), 'top-left', 20, 20)
                ->save(public_path($path));
    }

    /**
     * Remove photo files form disk
     *
     * @param string $filename
     * @param int $gallery_id
     * @return void
    */
    public function remove($filename, $path, $photoVariations)
    {
        foreach ($photoVariations as $variation => $dim)
        {
            @unlink($path . '/' . $variation . '/' . $filename);
        }
    }
    
    /**
     * Make smaller version of photo
     *
     * @param string $sourcePath
     * @param string $destinationPath
     * @param array $settings
     * @return void
    */
    public function resize($sourcePath, $destinationPath, $settings)
    {
        return Image::make(public_path($sourcePath))
                   ->fit($settings['w'], $settings['h'], function ($constraint) { $constraint->upsize(); })
                   ->save(public_path($destinationPath));
    }

	public function optimize($path)
	{
		$factory = new \ImageOptimizer\OptimizerFactory(array('jpegoptim_options' => array('--strip-all', '--all-progressive', '--max=80')));
		$optimizer = $factory->get();
		$optimizer->optimize($path);
	}
 
}
