<?php

namespace App\Mail\Auth;

use App\User;
use App\Mail\Mail;

/**
 * Class PasswordResetMail
 *
 * @package App\Mail\Auth
 */
class PasswordResetMail extends Mail
{
    /**
     * Model: User.
     *
     * @var \App\User
     */
    private $user;
    
    /**
     * Token
     *
     * @var string
     */
    private $token;
    
    /**
     * Create a new message instance.
     *
     * @param \App\User $user
     * @param string $token
     */
    public function __construct(User $user, $token)
    {
        // User
        $this->user = $user;
    
        // Token
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Mail: View data
        $this->viewData = [
            'data' => [
                'title' => trans('passwords.pages.recover.email.title'),
                'preview' => '',
                'message' => trans('passwords.pages.recover.email.message', [
                    'user_to_name' => is_null($this->user->rPerson) ? $this->user->email : $this->user->rPerson->name,
                    'site_url' => get_domain_name(),
                ]),
                'button' => [
                    'href' => route('password.reset', [
                        'token' => $this->token,
                        'email' => $this->user->email,
                    ]),
                    'text' => trans('passwords.pages.recover.email.button'),
                ],
                'note' => trans('passwords.pages.recover.email.note', [
                    'date' => $this->getPasswordResetExpiredDate(),
                ]),
                'user_to_email' => $this->user->email,
            ],
        ];
    
        // Mail: To
        $this->to($this->user);
    
        // Mail: Subject
        $this->subject($this->viewData['data']['title'].$this->getMailTitleSuffix());
    
        // Return
        return $this;
    }
    
    /**
     * Get password reset expire date.
     *
     * @return string
     */
    private function getPasswordResetExpiredDate()
    {
        // Date
        $date = (is_null($this->user->passwordReset)) ? now() : $this->user->passwordReset->created_at;
        
        // Return
        return $date->addMinutes(config('auth.passwords.users.expire', 0))->format('d.m.Y H:i');
    }
}
