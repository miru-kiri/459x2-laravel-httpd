<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userData,$shopData,$siteData,$castData,$title,$content,$timePlay,$createdAt,$criterialContent)
    {
        $this->userData = $userData;
        $this->shopData = $shopData;
        $this->siteData = $siteData;
        $this->castData = $castData;
        $this->title = $title;
        $this->content = $content;
        $this->timePlay = $timePlay;
        $this->createdAt = $createdAt;
        $this->criterialContent = $criterialContent;
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
        ->subject("新しい口コミが投稿されました。")
        ->view('mail.reviewAdmin')
        ->with([
            'userData' => $this->userData,
            'shopData' => $this->shopData,
            'siteData' => $this->siteData,
            'castData' => $this->castData,
            'title' => $this->title,
            'content' => $this->content,
            'timePlay' => $this->timePlay,
            'createdAt' => $this->createdAt,
            'criterialContent' => $this->criterialContent,
        ]);
    }
}
