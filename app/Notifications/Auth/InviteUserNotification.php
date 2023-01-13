<?php

namespace App\Notifications\Auth;

use App\Mail\Auth\InviteUserMail;
use App\Notifications\BaseNotification;

/**
 * Class InviteUserNotification
 *
 * @package App\Notifications\Auth
 */
class InviteUserNotification extends BaseNotification
{
    /**
     * @var string
     */
    private $token;
    
    /**
     * InviteUserNotification constructor.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }
    
    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \App\Mail\Auth\InviteUserMail
     */
    public function toMail($notifiable)
    {
        return (new InviteUserMail($notifiable, $this->token));
    }
}
