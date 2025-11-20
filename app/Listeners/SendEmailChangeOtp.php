<?php

namespace App\Listeners;

use App\Events\EmailChangeRequested;
use App\Mail\EmailChangeOtpMail;
use Illuminate\Support\Facades\Mail;

class SendEmailChangeOtp
{
    public function handle(EmailChangeRequested $event)
    {
        Mail::to($event->email)->send(new EmailChangeOtpMail($event->otp));
    }
}

