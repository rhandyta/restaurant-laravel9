<?php

namespace App\Listeners;

use App\Events\TransactionProcessEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

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
        // $pusher = new \Pusher\Pusher(config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'));

        // $pusher->trigger('transaction.' . 7, 'subscription_succeeded', [
        //     'event' => $event,
        //   ]);
        Log::debug('isi dari event confirm', [
            'event' => $event
        ]);
    }
}
