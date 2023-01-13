<?php

namespace App\Http\Controllers;

/**
 * Class LocationController
 *
 * @package App\Http\Controllers
 */
class LocationController extends Controller
{
    /**
     * MapController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Picker.
     *
     * @return \Illuminate\View\View
     */
    public function picker()
    {
        return view('location.picker')->with([
            'latitude' => (float) request('lat', '43.854901'),
            'longitude' => (float) request('lon', '18.418531'),
            'callback' => request('callback', 'noCallback'),
        ]);
    }
}