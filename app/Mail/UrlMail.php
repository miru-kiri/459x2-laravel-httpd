<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UrlMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title,$url)
    {
        $this->title = $title;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->from(env("MAIL_USERNAME"))
        ->subject("【コスモ天国ネット】URL認証用メール")
        ->view('mail.url')
        ->with([
            'title' => $this->title,
            'url' => $this->url,
        ]);
    }
}
