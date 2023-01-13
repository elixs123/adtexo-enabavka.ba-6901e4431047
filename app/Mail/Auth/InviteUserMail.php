<?php

namespace App\Mail\Auth;

use App\User;
use App\Mail\Mail;

/**
 * Class InviteUserMail
 *
 * @package App\Mail\Auth
 */
class InviteUserMail extends Mail
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
     * @param \App\User|mixed $user
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
                'title' => trans('user.emails.invite.title', [
                    'site_url' => get_domain_name(),
                ]),
                'preview' => '',
                'message' => trans('user.emails.invite.message', [
                    'user_to_name' => is_null($this->user->rPerson) ? $this->user->email : $this->user->rPerson->name,
                    'site_url' => get_domain_name(),
                    'user_email' => $this->user->email,
                ]),
                'button' => [
                    'href' => route('password.reset', [
                        'token' => $this->token,
                        'email' => $this->user->email,
                    ]),
                    'text' => trans('user.emails.invite.button'),
                ],
                'note' => trans('user.emails.invite.note', [
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
