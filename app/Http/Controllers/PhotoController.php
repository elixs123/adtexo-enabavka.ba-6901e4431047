<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Validator;
use Illuminate\Support\Str;
use App\Photo;

class PhotoController extends Controller
{
    public function postAjaxUpload()
    {
        // Handle upload(s) with input name "files[]" (array) or "files" (single file upload)

        $item_id = request()->get('item_id', 0);
        $folder = request()->get('folder');

        if (request()->hasFile('files') && $item_id > 0)
        {
            $all_uploads = request()->file('files');

            // We will store our uploads in public/uploads/basic
            $assetPath = '/assets/photos/gallery/original';
            $uploadPath = public_path($assetPath);
            
            // We need an empty arry for us to put the files back into
            $results = $error_messages = array();

            // Make sure it really is an array
            if (!is_array($all_uploads))
            {
                $all_uploads = array($all_uploads);
            }

            $error_messages = array();

            // Loop through all uploaded files
            foreach ($all_uploads as $upload)
            {
                // Ignore array member if it's not an UploadedFile object, just to be extra save
                if (!is_a($upload, 'Symfony\Component\HttpFoundation\File\UploadedFile'))
                {
                    continue;
                }

                $validator = Validator::make(
                        array('file' => $upload), 
                        array('file' => 'required|mimes:jpeg,jpg|image|max:5000')
                );

                if ($validator->passes())
                {
                    // Prepare new nice name for file
                    $new_filename = $item_id . '-' . Str::slug(basename($upload->getClientOriginalName(), $upload->getClientOriginalExtension())) . '-' . time() . '.jpg';

                    // Store our uploaded file in our folder                    
                    $upload->move($uploadPath, $new_filename);
                    
                    // Create photo variations      
                    
                    $photos_variations = config('picture.gallery_variations');
                    
                    foreach ($photos_variations as $variation => $dim)
                    {   
                        if($dim['w'] != null && $dim['h'] != null)
                        {
                            Image::make(public_path(config('picture.gallery_path') . '/original/' . $new_filename))
                                    ->resize($dim['w'], null, function ($constraint) {$constraint->aspectRatio();})
                                    ->crop($dim['w'], $dim['h'])
                                    ->save(public_path(config('picture.gallery_path') . '/' . $variation . '/' . $new_filename));                              
                        }
                    }                    
                    
                    // Insert photo data into table                    
                    $data['name'] = $new_filename;
                    $data['item_id'] = $item_id;

                    $photo = new Photo();
                    $photo->module = $folder;
                    $photo->addPhoto($data);
                    
                    // Set our results to have our asset path
                    $name = '/' . config('picture.gallery_path') . '/small/' . $new_filename;
                    $results[] = compact('name');
                    
                } 
                else
                {
                    // Collect error messages
                    $error_message = $upload->getClientOriginalName() . '":' . $validator->messages()->first('file');
                    $error_messages[] = compact('error_message');
                }
            }

            // return our results in a files object
            return array(
                'files' => $results,
                'errors' => $error_messages
            );
        }
    }
    
    public function postRemove()
    {
        $photo_id = request()->get('id', 0);
        $folder = request()->get('folder');        
        
        $photo = new Photo();
        $photo->module = $folder;        
        $photo_data = $photo->getPhoto($photo_id);
                
        if (is_object($photo_data))
        {
            $photo_data->removePhoto($photo_id);
            
            $photos_variations = config('picture.gallery_variations');
            foreach ($photos_variations as $variation => $dim)
            {
                @unlink(public_path(config('picture.gallery_path') . '/' . $variation . '/' . $photo_data->name));
            }                        
        }                
    }    
}