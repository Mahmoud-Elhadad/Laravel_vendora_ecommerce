<?php

namespace App\Listeners;

use App\Events\MerchantRejected;
use App\Mail\MerchantRejectedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailToMerchantRejected implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(MerchantRejected $event): void
    {
        Mail::to($event->merchant->user->email)->send(new MerchantRejectedMail($event->merchant));
    }
}
