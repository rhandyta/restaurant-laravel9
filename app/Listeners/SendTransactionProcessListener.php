<?php

namespace App\Listeners;

use App\Events\TransactionProcessEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendTransactionProcessListener
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
     * @param  \App\Events\TransactionProcessEvent  $event
     * @return void
     */
    public function handle(TransactionProcessEvent $event)
    {
        Log::debug('isi dari event confirm', [
            'event' => $event
        ]);
    }
}
