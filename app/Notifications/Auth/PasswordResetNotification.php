<?php

namespace App\Notifications\Auth;

use App\Mail\Auth\PasswordResetMail;
use App\Notifications\BaseNotification;

/**
 * Class PasswordResetNotification
 *
 * @package App\Notifications\Auth
 */
class PasswordResetNotification extends BaseNotification
{
    /**
     * @var string
     */
    private $token;
    
    /**
     * PasswordResetNotification constructor.
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
     * @return \App\Mail\Auth\PasswordResetMail
     */
    public function toMail($notifiable)
    {
        return (new PasswordResetMail($notifiable, $this->token));
    }
}
