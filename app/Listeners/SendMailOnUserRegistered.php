<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\UserAddedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailOnUserRegistered implements ShouldQueue
{

    use InteractsWithQueue;
    
    public $tries = 3;
    public $backoff = 15;
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
    public function handle(UserRegistered $event): void
    {
        Mail::to($event->user['email'])->send(new UserAddedMail($event->user));
    }
}
