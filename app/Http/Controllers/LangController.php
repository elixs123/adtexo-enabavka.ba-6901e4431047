<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * Class LangController
 *
 * @package App\Http\Controllers
 */
class LangController extends Controller
{
    /**
     * Lang: Change.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(Request $request)
    {
        $lang_id = $request->get('lang_id', config('app.locale'));
        
        if (is_null(config('app.locales.'.$lang_id))) {
            $lang_id = config('app.locale');
        }
        
        app()->setLocale($lang_id);
    
        Cookie::queue(Cookie::make('lang_id', $lang_id, 525600));
        
        return redirect()->back();
    }
}