<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Input;

class Xss
{    
    /**
     * XSS
     * @param string $value
     * @param array $except
     * @param string $key 
     * @return string
     */
    public static function clean($value, $except = array(), $key = null)
    {
        if (isset($except[0]) && in_array($key, $except)) 
        {
            return $value;
        }  
        else
        {
            return htmlspecialchars(strip_tags($value));
        }
      }   
        
    /*
     * Method to strip tags globally.
     * @param array $except
     * @return void
     */
    public static function globalClean($except = array())
    {
        $sanitized = static::arrayStripTags(Input::get(), $except);
        Input::merge($sanitized);
    }

    /**
     * XSS
     * @param string $array
     * @param array $except
     * @return $array
     */    
    public static function arrayStripTags($array, $except = array())
    {
        $result = array();

        foreach ($array as $key => $value) 
        {
            $key = static::clean($key);                      
            
            if (is_array($value))
            {
                $result[$key] = static::arrayStripTags($value, $except);
            }
            else
            {
                $result[$key] = trim(static::clean($value, $except, $key));
            }
        }

        return $result;
    }          
}