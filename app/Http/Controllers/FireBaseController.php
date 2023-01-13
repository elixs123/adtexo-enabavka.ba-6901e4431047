<?php

namespace App\Http\Controllers;

use App\Notifications\Reminder\TaskReminderNotification;
use App\Reminder;
use App\Task;
use App\User;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Artisan;

class FireBaseController extends Controller
{
    public function test()
    {
        try {
            $serviceAccount = ServiceAccount::fromJsonFile(storage_path('firebase_credentials.json'));
        } catch (InvalidMessage $e) {
            print_r($e->errors());
        }
        
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        
        $messaging = $firebase->getMessaging();
    
        $multi = true;
    
        $deviceTokens = [
            'dprgw6Sm8g0:APA91bE-WTMKN3cZ2FphzUlJogpsEmqTzAblq47_kDpec3irG8d5ZXMM4UjXpasykU9IApZFZACMNLbGuit3F39HHE7ajjhMcE3buHh4k6BjnFQFvJCoa-xgNaYxPYv0TZbanSKFeU5Q',
            'dDJPM_rmMsE:APA91bGRX1hqucD8NMmyp-b76U1n8iXTiMErCyoQYhU2NWniltXupf8tUnrl_WUZ-gqdGmtKTUKeRzWbS2q6nkg5jxtCsIGQAJI7IsuSUyK-LfoOnMX4DyR2-QmOWZyP6Y0vx4EejIQV',
            'dtaQ2qpRAjw:APA91bFQPN0WD5czRzzfNJfwjXiMp0FLn7KmLE9GWKYz4udyfE6txpdQIDSYkqxWywqcmG1hbOZQ9JD4CJvrJ8ikJb8LSOv08udPz6qcmPdl-cc5mjkTc1Ke8isnc1UUhSk5KhrLNapL',
            'd5LUFi1KxP4:APA91bEGVnOYbrhsUZrEW1e7-533jq7ab4MNvdsfKO3wTAjRg-wZzZN4zHitWsIMJnVATlselOCDBf-MTa6fBbReQ35Tg72GxYbzOaQdqdRuExhY8YA2udMrlsdoRJUZ1MDDLAjrUxKt',
//            'cOyW7OUDk3k:APA91bGxRnmcx-LTGYZmbbkWK8HkwpvqOQKXxYpG-2DVS3CC64bOfxnwbGEpvNj1Q53xIic_WHjuxIa-EZCYKY-qTWyOeasWNnWBOEwRzt1Vj_S-5mWCzeVpJEwbYjGUv9UP_gxp07_Q',
        ];
        
        $deviceToken = 'dprgw6Sm8g0:APA91bE-WTMKN3cZ2FphzUlJogpsEmqTzAblq47_kDpec3irG8d5ZXMM4UjXpasykU9IApZFZACMNLbGuit3F39HHE7ajjhMcE3buHh4k6BjnFQFvJCoa-xgNaYxPYv0TZbanSKFeU5Q';
    
        $message = $multi ? CloudMessage::new() : CloudMessage::withTarget('token', $deviceToken);
        
        $message = $message->withNotification([
            'title' => 'FCM Message '.time(),
            'body' => 'This is a message from FCM 1'.time(),
        ]);
    
        $message = $message->withAndroidConfig([
            'ttl' => '86400s',
            'priority' => 'NORMAL',
            'notification' => [
                'title' => 'FCM Message '.time(),
                'body' => 'This is a message from FCM 1'.time(),
                'click_action' => url('/'),
            ],
        ]);
    
        $message = $message->withWebPushConfig([
            'headers' => [
                'Urgency' => 'high',
                'TTL' => '86400',
            ],
            'notification' => [
                'title' => 'FCM Message '.time(),
                'body' => 'This is a message from FCM to web 1'.time(),
                //'requireInteraction' => 'true',
                'icon' => asset('android-icon-72x72.png'),
            ],
            'fcm_options' => [
                'link' => url('/'),
            ],
        ]);
    
        if ($multi) {
            $report = $messaging->sendMulticast($message, $deviceTokens);
    
            echo 'Successful sends: '.$report->successes()->count().'<br>';
            echo 'Failed sends: '.$report->failures()->count().'<br>';
    
            if ($report->hasFailures()) {
                foreach ($report->failures()->getItems() as $failure) {
                    echo $failure->error()->getMessage().'<br>';
                }
            }
        } else {
            try {
                $messaging->validate($message);
            } catch (InvalidMessage $e) {
                print_r($e->errors());
            }
            
            $sent = $messaging->send($message);
            
            echo $sent['name'];
        }
        return '<br>Done';
    }
    
    public function task()
    {
        $reminder = (new Reminder())->getOne(20);
        
        if (is_null($reminder)) {
            abort(404);
        }
        
        $task = (new Task())->getOne($reminder->item_id);
        
        $user = (new User())->getOne($reminder->user_id);
        
        $user->notify(new TaskReminderNotification($task, $reminder));
    }
    
    public function notify()
    {
        return Artisan::call('schedule:run');
    }
    
    public function notifyOwner()
    {
        $task = (new Task())->getOne(1407);
        
        // dd($task, $task->rReminders);
        
        foreach ($task->rReminders as $reminder) {
            // $reminder->rUser->notify(new TaskReminderNotification($task, $reminder));
            
            // dd($task->toArray(), $task->notify_owner);
            if ($task->notify_owner) {
                $task->rCreatedBy->notify(new TaskReminderNotification($task, $reminder));
            }
        }
        
        return response()->json([
            'message' => 'notify-owner',
        ]);
    }
}