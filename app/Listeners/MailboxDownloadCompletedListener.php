<?php

namespace App\Listeners;

use App\Events\DownloadMailboxFinishedEvent;

class MailboxDownloadCompletedListener
{
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
    public function handle(DownloadMailboxFinishedEvent $event): void
    {
        info("mailbox download finished for {$event->mailbox}");
    }
}
