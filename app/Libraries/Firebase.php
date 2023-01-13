<?php

namespace App\Libraries;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

/**
 * Trait Firebase
 *
 * @package App\Libraries
 */
trait Firebase
{
    /**
     * Get service account.
     *
     * @return \Kreait\Firebase\ServiceAccount
     */
    protected function getServiceAccount()
    {
        return ServiceAccount::fromJsonFile(storage_path('firebase_credentials.json'));
    }
    
    /**
     * Get Firebase.
     *
     * @return \Kreait\Firebase
     */
    protected function getFirebase()
    {
        return (new Factory)
            ->withServiceAccount($this->getServiceAccount())
            ->create();
    }
    
    /**
     * Build message.
     *
     * @param \Kreait\Firebase\Messaging\CloudMessage $message
     * @param array $data
     * @param bool $webPush
     * @param bool $android
     * @return \Kreait\Firebase\Messaging\CloudMessage
     */
    protected function buildMessage($message, $data, $webPush = true, $android = false)
    {
        $title = $data['title'];
        $body = strip_tags(str_replace(['<br>', '<br/>', '<br />', "\n", "\r"], ' ', $data['body']));
        
        // $message = $message->withNotification([
        //     'title' => $data['title'],
        //     'body' => $data['body'],
        // ]);
        
        if ($webPush) {
            $message = $message->withWebPushConfig([
                'headers' => [
                    'Urgency' => 'high',
                    'TTL' => '43200',
                ],
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    //'requireInteraction' => 'true',
                    'icon' => asset('android-icon-72x72.png', true),
                ],
                'fcm_options' => [
                    'link' => url($data['url'], [], true),
                ],
            ]);
        }
        
        if ($android) {
            $message = $message->withAndroidConfig([
                'ttl' => '43200s',
                'priority' => 'HIGH',
                // 'notification' => [
                //     'title' => $data['title'],
                //     'body' => $data['body'],
                //     'click_action' =>  url($data['url'], [], true),
                //     'image' => asset('android-icon-72x72.png', true),
                // ],
            ]);
        }
        
        if (isset($data['data']) && count($data['data'])) {
            $message = $message->withData($data['data']);
        }
        
        return $message;
    }
    
    /**
     * Get device tokens.
     *
     * @param \App\User $user
     * @return array
     */
    protected function getDeviceTokens($user)
    {
        return $user->firebaseTokens()->get(['token'])->pluck('token')->toArray();
    }
}