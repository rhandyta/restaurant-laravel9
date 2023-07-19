<?php

namespace App\Services\Midtrans;

use App\Jobs\MailOrderJob;
use App\Mail\MailOrderTransaction;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CallbackService extends Midtrans
{

  protected $transaction;


  public function __construct($transaction)
  {
    parent::__construct();
    $this->transaction = $transaction;
  }

  public function webHook()
  {
    try {
      DB::beginTransaction();
      $notif = new \Midtrans\Notification();
      $transaction_status = $notif->transaction_status;
      $type = $notif->payment_type;
      $order_id = $notif->order_id;
      $transactionUpdate = Order::query()
        ->with(['user', 'detailorders' => function ($q) {
          $q->with(['foodlist' => function ($q) {
            $q->with('foodimages');
          }]);
        }])
        ->where('order_id', '=', $order_id)->first();

      if ($transaction_status == 'settlement') {
        $transactionUpdate->update([
          'transaction_status' => $notif->transaction_status,
          'transaction_message' => "Transaction order: " . $order_id . " successfully transferred using " . $transactionUpdate->bank,
          'transaction_code' => $notif->status_code,
        ]);
        MailOrderJob::dispatchIf($transactionUpdate, $transactionUpdate, $transactionUpdate->user, $transaction_status)->delay(now()->addMinutes(1));
      } else if ($transaction_status == 'pending') {
        $transactionUpdate->update([
          'transaction_status' => $notif->transaction_status,
          'transaction_message' => "Waiting for the customer to finish the transaction order: " . $order_id . " using " . $transactionUpdate->bank,
          'transaction_code' => $notif->status_code,
        ]);
        MailOrderJob::dispatchIf($transactionUpdate, $transactionUpdate, $transactionUpdate->user, $transaction_status)->delay(now()->addMinutes(1));
      } else if ($transaction_status == 'deny') {
        $transactionUpdate->update([
          'transaction_status' => $notif->transaction_status,
          'transaction_message' => "Payment using " . $transactionUpdate->bank . " for transaction order: " . $order_id . " is denied.",
          'transaction_code' => $notif->status_code,
        ]);
        MailOrderJob::dispatchIf($transactionUpdate, $transactionUpdate, $transactionUpdate->user, $transaction_status)->delay(now()->addMinutes(1));
      } else if ($transaction_status == 'expire') {
        $transactionUpdate->update([
          'transaction_status' => $notif->transaction_status,
          'transaction_message' => "Payment using " . $transactionUpdate->bank . " for transaction order: " . $order_id . " is expired.",
          'transaction_code' => $notif->status_code,
        ]);
        MailOrderJob::dispatchIf($transactionUpdate, $transactionUpdate, $transactionUpdate->user, $transaction_status)->delay(now()->addMinutes(1));
      } else if ($transaction_status == 'cancel') {
        $transactionUpdate->update([
          'transaction_status' => $notif->transaction_status,
          'transaction_message' => "Payment using " . $transactionUpdate->bank . " for transaction order: " . $order_id . " is canceled.",
          'transaction_code' => $notif->status_code,
        ]);
        MailOrderJob::dispatchIf($transactionUpdate, $transactionUpdate, $transactionUpdate->user, $transaction_status)->delay(now()->addMinutes(1));
      }
      DB::commit();
    } catch (\Exception $e) {
      Mail::to('sendingemail117@gmail.com')->send(new MailOrderTransaction($order_id, $e->getMessage()));
      DB::rollBack();
    }
  }
}
