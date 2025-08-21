<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReserveMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userData,$startTime,$shopData,$castData,$courseName,$amount)
    {
        $this->userData = $userData;
        $this->startTime = $startTime;
        $this->shopData = $shopData;
        $this->castData = $castData;
        $this->courseName = $courseName;
        $this->amount = $amount;
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
        ->subject("【コスモ天国ネット】仮予約完了メール")
        ->view('mail.reserve')
        ->with([
            'userData' => $this->userData,
            'startTime' => $this->startTime,
            'shopData' => $this->shopData,
            'castData' => $this->castData,
            'courseName' => $this->courseName,
            'amount' => $this->amount,
        ]);
    }
}
