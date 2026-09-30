<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Notifications\RegisterClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNotificationRegisterClient implements ShouldQueue
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
        Notification::send($event->admins , new RegisterClient($event->content));
    }
}
