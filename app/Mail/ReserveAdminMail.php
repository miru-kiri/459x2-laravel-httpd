<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReserveAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userData,$startTime,$shopData,$siteData,$castData,$courseName,$amount,$adr,$memo)
    {
        $this->userData = $userData;
        $this->startTime = $startTime;
        $this->shopData = $shopData;
        $this->siteData = $siteData;
        $this->castData = $castData;
        $this->courseName = $courseName;
        $this->amount = $amount;
        $this->adr = $adr;
        $this->memo = $memo;
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
        ->subject("新しい予約が入りました。(" . date('Y/m/d H:i',strtotime($this->startTime))."):".$this->siteData->name)
        ->view('mail.reserveAdmin')
        ->with([
            'userData' => $this->userData,
            'startTime' => $this->startTime,
            'shopData' => $this->shopData,
            'siteData' => $this->siteData,
            'castData' => $this->castData,
            'courseName' => $this->courseName,
            'amount' => $this->amount,
            'adr' => $this->adr,
            'note' => $this->memo,
        ]);
    }
}
