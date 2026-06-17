<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReplyToMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $bodyText;

    public function __construct($subjectLine, $bodyText)
    {
        $this->subjectLine = $subjectLine;
        $this->bodyText = $bodyText;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.reply')
            ->with(['body' => $this->bodyText]);
    }
}
