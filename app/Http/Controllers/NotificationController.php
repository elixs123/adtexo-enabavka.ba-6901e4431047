<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;

/**
 * Class NotificationController
 *
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{
    /**
     * @var \Illuminate\Notifications\DatabaseNotification
     */
    private $notification;
    
    /**
     * NotificationController constructor.
     *
     * @param \Illuminate\Notifications\DatabaseNotification $notification
     */
    public function __construct(DatabaseNotification $notification)
    {
        $this->notification = $notification;
    }
    
    /**
     * Index.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $items = $this->notification->where('notifiable_id', auth()->id())->orderBy('created_at', 'desc')->paginate(12);
    
        return view('notification.index')->with([
            'items' => $items,
        ]);
    }
    
    /**
     * Show.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $notification = $this->notification->where('id', $id)->first();
        
        if (is_null($notification)) {
            abort(404);
        }
        
        $notification->markAsRead();
        
        $redirect = ($notification->data['url']) ? url($notification->data['url']) : url('/');
        
        return redirect($redirect);
    }
    
    /**
     * Toggle.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function toggle($id)
    {
        $notification = $this->notification->where('id', $id)->first();
        
        if (is_null($notification)) {
            abort(404);
        }
        
        if ($notification->read()) {
            $notification->markAsUnread();
        } else {
            $notification->markAsRead();
        }
    
        return response()->json([
            'notification' => [
                'id' => $notification->id,
                'status' => $notification->read() ? 'read' : 'unread',
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Mark as read notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function read()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
    
        return response()->json([
            'status' => 1,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}