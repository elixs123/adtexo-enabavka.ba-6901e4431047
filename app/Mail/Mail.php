<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail as MailFacade;

/**
 * Class Mail
 *
 * @package App\Mail
 */
class Mail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The view to use for the message.
     *
     * @var string
     */
    public $view = 'emails.template';
    
    /**
     * The view data for the message.
     *
     * @var array
     */
    public $viewData = [];

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
    
    /**
     * Send email.
     *
     * @param bool $forceSend
     * @return void
     */
    public function sendMail($forceSend = false)
    {
        // // Check
        // if ((config('app.env') == 'production') || ($forceSend)) {
        //     // Mail: Send @ToDo: Change later
        //     MailFacade::send($this);
        // }
    }
    
    /**
     * Get mail title suffix.
     *
     * @return string
     */
    protected function getMailTitleSuffix()
    {
        return ' @ '.get_domain_name().' '.now()->format('d.m.Y H:i');
    }
}
