<?php

namespace App\Http\Controllers;

use App\Document;
use App\Mail\Auth\InviteUserMail;
use App\Mail\Auth\PasswordResetMail;
use App\Mail\Document\TrackingCodeMail;
use App\Mail\Exception\ErrorMail;
use App\User;

/**
 * Class EmailController
 *
 * @package App\Http\Controllers
 */
class EmailController extends Controller
{
    /**
     * @return string
     */
    public function errorMail()
    {
        return (new ErrorMail('RuntimeException', request(), 'No application encryption key has been specified.', 'app/Http/Controllers/EmailController.php', 20))->render();
    }
    
    /**
     * @return string
     */
    public function passwordReset()
    {
        $user = User::findOrFail(2);
        
        return (new PasswordResetMail($user, 'SomeRandomToken'))->render();
    }
    
    /**
     * @return string
     */
    public function inviteUser()
    {
        $user = User::findOrFail(2);
        
        return (new InviteUserMail($user, 'SomeRandomToken'))->render();
    }
    
    /**
     * @return string
     */
    public function documentTrack()
    {
        $document = Document::findOrFail(11289);
    
        // $document->sendTrackingCodeNotification();
        return (new TrackingCodeMail($document))->render();
    }
}
