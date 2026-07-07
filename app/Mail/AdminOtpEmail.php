<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminOtpEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $accessKey;

    public function __construct(string $otp, string $accessKey)
    {
        $this->otp = $otp;
        $this->accessKey = $accessKey;
    }

    public function build()
    {
        return $this->subject('Your Admin Login Code and Access Key')
            ->markdown('emails.admin-otp', [
                'otp' => $this->otp,
                'accessKey' => $this->accessKey,
            ]);
    }
}
