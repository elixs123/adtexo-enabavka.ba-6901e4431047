<?php

namespace App\Support\Notifications;

/**
 * Trait NotificationHelper
 *
 * @package App\Support\Notifications
 */
trait NotificationHelper
{
    /**
     * Notification: Title.
     *
     * @return string
     */
    protected function getNotificationTitle()
    {
        return 'Nabavke.ba';
    }
    
    /**
     * Notification: Color.
     *
     * @return string
     */
    protected function getNotificationColor()
    {
        return '#000000';
    }
    
    /**
     * Notification: Attachment url.
     *
     * @return string
     */
    protected function getAttachmentUrl()
    {
        return asset('assets/img/fb-share.jpg');
    }
}
