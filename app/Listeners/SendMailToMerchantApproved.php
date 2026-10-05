<?php

namespace App\Listeners;

use App\Events\MerchantApproved;
use App\Mail\MerchantApprovedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailToMerchantApproved implements ShouldQueue
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
    public function handle(MerchantApproved $event): void
    {
        Mail::to($event->merchant->user->email)->send(new MerchantApprovedMail($event->merchant));
    }
}
