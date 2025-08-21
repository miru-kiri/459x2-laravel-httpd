<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OptMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$code,$url)
    {
        $this->email = $email;
        $this->code = $code;
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
        ->subject("【コスモ天国ネット】メールアドレス変更認証用メール")
        ->view('mail.opt')
        ->with([
            'email' => $this->email,
            'code' => $this->code,
            'url' => $this->url,
        ]);
    }
}
