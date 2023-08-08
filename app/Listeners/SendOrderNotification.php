<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendOrderNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderEvent  $event
     * @return void
     */
    public function handle(OrderEvent $event)
    {
        Log::debug('isi dari event', [
            'event' => $event
        ]);
    }
}
