<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Libraries\Firebase;
use RuntimeException;
use Kreait\Firebase\Messaging\CloudMessage;

/**
 * Class FirebaseChannel
 *
 * @package App\Channels
 */
class FirebaseChannel
{
    use Firebase;
    
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if ($this->isEnabled()) {
            $data = $notification->toFirebase($notifiable);
            
            $message = $this->getMessage($data);
            
            $deviceTokens = $this->getDeviceTokens($notifiable);
            
            if (count($deviceTokens)) {
                $messaging = $this->getFirebase()->getMessaging();
        
                $messaging->sendMulticast($message, $deviceTokens);
            }
        }
        
    }
    
    /**
     * Is Firebase service enabled.
     *
     * @return bool
     */
    protected function isEnabled()
    {
        return config('services.firebase.enabled');
    }
    
    /**
     * Get message.
     *
     * @param $data
     * @return \Kreait\Firebase\Messaging\CloudMessage
     */
    protected function getMessage($data)
    {
        return $this->buildMessage(CloudMessage::new(), $data);
    }
}