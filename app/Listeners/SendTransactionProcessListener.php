<?php

namespace App\Listeners;

use App\Events\TransactionProcessEvent;
use App\Jobs\MailOrderJob;
use App\Mail\MailTransaction;
use App\Models\Order;
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

        $transaction = Order::query()
        ->with(['detailorders' => function ($q) {
          $q->with(['foodlist' => function ($q) {
            $q->with('foodimages');
          }]);
        }])
        ->where('id', '=', $event->transaction['id'])->first();
        Log::info('datanya adalah' . $event->transaction['id']);
        Log::info('transaction adalah' . $transaction);
        MailOrderJob::dispatch($transaction, $transaction->email, $transaction->transaction_status)->delay(now()->addSeconds(10));
    }
}
