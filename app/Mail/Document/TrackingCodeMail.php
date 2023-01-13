<?php

namespace App\Mail\Document;

use App\Document;
use App\Mail\Mail;

/**
 * Class TrackingCodeMail
 *
 * @package App\Mail\Document
 */
class TrackingCodeMail extends Mail
{
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * TrackingCodeMail constructor.
     *
     * @param \App\Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user_name = array_get($this->document->shipping_data, 'name', 'John Doe');
        $user_email = array_get($this->document->shipping_data, 'email', 'john.doe@domain.com');
        
        // Mail: View data
        $this->viewData = [
            'data' => [
                'title' => trans('document.emails.tracking.title'),
                'preview' => '',
                'message' => trans('document.emails.tracking.message', [
                    'user_to_name' => $user_name,
                    'document_number' => $this->document->id.'/'.$this->document->created_at->format('Y'),
                    'user_email' => $user_email,
                ]),
                'button' => [
                    'href' => $this->document->public_url,
                    'text' => trans('document.emails.tracking.button'),
                ],
                'user_to_email' => $user_email,
            ],
        ];
        
        // Mail: To
        $this->to($user_email);
        
        // Mail: Subject
        $this->subject($this->viewData['data']['title'].$this->getMailTitleSuffix());
        
        // Return
        return $this;
    }
}
