<?php

namespace App;

/**
 * Trait FileHelper
 *
 * @package App
 */
trait FileHelper
{
    /**
     * @param string $field
     * @param string $fileName
     * @param string $path
     * @param \Illuminate\Http\Request|mixed $request
     * @return string|null
     */
    public function uploadFile($field, $fileName, $path, $request, $storagePath = false)
    {
        if ($request->hasFile($field)) {
            $file = $request->file($field);
    
            $fileNameNew = $this->prepareFileName($fileName, $file->getClientOriginalExtension());
    
            $file->move($storagePath ? storage_path($path) : public_path($path), $fileNameNew);
    
            return $fileNameNew;
        }
        
        return null;
    }
    
    /**
     * Prepare nice and unique file name for file
     *
     * @param string $realName
     * @param string $ext
     * @return string
    */
    public function prepareFileName($realName, $ext = 'dat')
    {
        return time() . '-' . str_slug($realName) . '.' . $ext;
    }
}
