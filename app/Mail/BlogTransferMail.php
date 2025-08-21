<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BlogTransferMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title,$content,$post_email,$path)
    {
        $this->title = $title;
        $this->content = $content;
        $this->post_email = $post_email;
		$this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$mail = $this
        ->from(env("MAIL_USERNAME"))
        ->subject($this->title)
        ->view('mail.blogTransferMail')
        ->with([
            'content' => $this->content,
        ]);

        // $pathが設定されていて、そのファイルが存在する場合に添付
        if ($this->path) {
            $mail->attach(public_path('/storage/'.$this->path));
        }
        return $mail;
    }
}
