<?php

namespace App\Mail\Exception;

use App\Mail\Mail;
use Illuminate\Http\Request;

/**
 * Class ErrorMail
 *
 * @package App\Mail\Exception
 */
class ErrorMail extends Mail
{
    /**
     * The view to use for the message.
     *
     * @var string
     */
    public $view = 'emails.error';
    
    /**
     * Type.
     *
     * @var string
     */
    private $type;
    
    /**
     * Request.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    
    /**
     * Model: User.
     *
     * @var \App\User
     */
    private $user;
    
    /**
     * Message.
     *
     * @var string
     */
    private $message;
    
    /**
     * @var string
     */
    private $file;
    
    /**
     * @var int
     */
    private $line;
    
    /**
     * Create a new message instance.
     *
     * @param string $type
     * @param \Illuminate\Http\Request $request
     * @param string $message
     * @param string $file
     * @param int $line
     */
    public function __construct($type, Request $request, $message, $file, $line)
    {
        $this->type = $type;
        $this->request = $request;
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // // User
        // $this->user = auth()->user();
        
        // // Mail: View data
        // $this->viewData = [
        //     'data' => [
        //         'title' => 'Error: '.$this->type,
        //         'preview' => $this->message,
        //         'message' => $this->message,
        //         'file' => str_replace(app_path(), 'app', $this->file),
        //         'line' => $this->line,
        //         'user_to_email' => 'support@lampa.ba',
        //     ],
        //     'request' => [
        //         'REMOTE_ADDRESS' => $this->request->ip(),
        //         'REQUEST_METHOD' => $this->request->server('REQUEST_METHOD'),
        //         'REQUEST_URI' => url($this->request->server('REQUEST_URI')),
        //         'HTTP_USER_AGENT' => $this->request->server('HTTP_USER_AGENT'),
        //     ],
        //     'query' => $this->request->query(),
        //     'user' => [
        //         'name' => is_null($this->user) ? 'John Doe' : is_null($this->user->rPerson) ? null : $this->user->rPerson->name,
        //         'mail' => is_null($this->user) ? 'john.doe@domain.com' : $this->user->email,
        //     ],
        // ];
    
        // // Mail: To
        // $this->to('emir.agic@lampa.ba', 'Emir Agić');
        // $this->cc('nikola.vujovic@ics.ba', 'Nikola Vujović');
    
        // // Mail: Subject
        // $this->subject($this->type.$this->getMailTitleSuffix());
    
        // // Return
        // return $this;
    }
}
