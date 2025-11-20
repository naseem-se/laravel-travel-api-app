<?php

namespace App\Listeners;

use App\Events\UserEmailVerification;
use App\Mail\UserMailVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendUserEmailVerification extends Mailable
{

    use Queueable, SerializesModels;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserEmailVerification $event): void
    {
        $user = $event->user;
        Mail::to($user->email)->send(new UserMailVerification($user));
    }
}
