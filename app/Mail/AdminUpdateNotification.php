<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $content;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function build()
    {
        $subject = 'SDA MUBS Updates';

        return $this->subject($subject)
            ->view('emails.admin-update-notification')
            ->with(['content' => $this->content]);
    }
}
