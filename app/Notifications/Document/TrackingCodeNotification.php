<?php

namespace App\Notifications\Document;

use App\Document;
use App\Mail\Document\TrackingCodeMail;
use App\Notifications\BaseNotification;

/**
 * Class TrackingCodeNotification
 *
 * @package App\Notifications\Document
 */
class TrackingCodeNotification extends BaseNotification
{
    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \App\Mail\Document\TrackingCodeMail
     */
    public function toMail($notifiable)
    {
        return (new TrackingCodeMail($notifiable));
    }
}
