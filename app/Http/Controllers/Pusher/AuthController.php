<?php

namespace App\Http\Controllers\Pusher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use Pusher\PusherException;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Pusher
 */
class AuthController extends Controller
{
    /**
     * Pusher.
     *
     * @var \Pusher\Pusher
     */
    private $pusher;
    
    /**
     * AuthController constructor.
     *
     * @throws PusherException
     */
    public function __construct()
    {
        $this->init();
    }
    
    /**
     * Init.
     *
     * @throws \Pusher\PusherException
     */
    private function init()
    {
        $config = config('broadcasting.connections.pusher');
        
        $this->pusher = new Pusher($config['key'], $config['secret'], $config['app_id'], [
            'cluster' => $config['options']['cluster'],
            'useTLS' => $config['options']['forceTLS'],
        ]);
    }
    
    /**
     * Check.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Pusher\PusherException
     */
    public function check(Request $request)
    {
        return Auth::check() ? $this->getAuthResponse($request) : $this->getErrorResponse();
    }
    
    /**
     * Auth response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Pusher\PusherException
     */
    private function getAuthResponse(Request $request)
    {
        $user = Auth::user();
        
        $avatar = 'assets/pictures/user/small_' . $user->photo;
        
        $presence_data = [
            'name' => is_null($user->client) ? $user->email : $user->client->name,
            'avatar' => is_file(public_path($avatar)) ? asset($avatar) : asset('assets/img/no_photo.jpg'),
            'role' => implode(', ', $user->roles->pluck('label')->toArray()),
        ];
        
        $data = $this->pusher->presence_auth($request->get('channel_name'), $request->get('socket_id'), $user->id, $presence_data);
        
        return response($data, 200);
    }
    
    /**
     * Errors response.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function getErrorResponse()
    {
        return response('Access denied!', 403);
    }
}
