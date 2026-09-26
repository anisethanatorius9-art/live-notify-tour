<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingReceiptEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Payment $payment)
    {
        $this->payment->loadMissing('booking.tourist', 'booking.service');
    }

    public function build(): static
    {
        return $this->subject('Your LTN booking receipt')
            ->markdown('emails.booking-receipt');
    }
}
