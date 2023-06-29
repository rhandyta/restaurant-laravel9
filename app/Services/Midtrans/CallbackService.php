<?php

namespace App\Services\Midtrans;

use App\Mail\MailOrderTransaction;
use App\Mail\MailTransaction;
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
    DB::beginTransaction();
    try {
      $notif = new \Midtrans\Notification();
      $transaction = $notif->transaction_status;
      $type = $notif->payment_type;
      $order_id = $notif->order_id;

      $transaction = $notif->transaction_status;
      $type = $notif->payment_type;
      $order_id = $notif->order_id;
      $transactionUpdate = Order::query()
        ->with(['user', 'detailorders' => function ($q) {
          $q->with(['foodlist' => function ($q) {
            $q->with('foodimages');
          }]);
        }])
        ->where('order_id', '=', $order_id)->first();

      if ($transaction == 'settlement') {
        $transactionUpdate->update([
          'transaction_status' => $notif->transaction_status,
          'transaction_message' => "Transaction order: " . $order_id . " successfully transferred using " . $type,
          'transaction_code' => $notif->status_code,
        ]);
        Mail::to($transactionUpdate->user->email)->send(new MailTransaction($transactionUpdate));
      } else if ($transaction == 'pending') {
        $transactionUpdate->update([
          'transaction_status' => $notif->transaction_status,
          'transaction_message' => "Waiting for the customer to finish the transaction order: " . $order_id . " using " . $type,
          'transaction_code' => $notif->status_code,
        ]);
        Mail::to($transactionUpdate->user->email)->send(new MailOrderTransaction($transactionUpdate));
      } else if ($transaction == 'deny') {
        $transactionUpdate->update([
          'transaction_status' => $notif->transaction_status,
          'transaction_message' => "Payment using " . $type . " for transaction order: " . $order_id . " is denied.",
          'transaction_code' => $notif->status_code,
        ]);
        Mail::to($transactionUpdate->user->email)->send(new MailOrderTransaction($transactionUpdate));
      } else if ($transaction == 'expire') {
        $transactionUpdate->update([
          'transaction_status' => $notif->transaction_status,
          'transaction_message' => "Payment using " . $type . " for transaction order: " . $order_id . " is expired.",
          'transaction_code' => $notif->status_code,
        ]);
        Mail::to($transactionUpdate->user->email)->send(new MailOrderTransaction($transactionUpdate));
      } else if ($transaction == 'cancel') {
        $transactionUpdate->update([
          'transaction_status' => $notif->transaction_status,
          'transaction_message' => "Payment using " . $type . " for transaction order: " . $order_id . " is canceled.",
          'transaction_code' => $notif->status_code,
        ]);
        Mail::to($transactionUpdate->user->email)->send(new MailOrderTransaction($transactionUpdate));
      }
      DB::commit();
    } catch (\Exception $e) {

      // Penanganan kesalahan
      // Misalnya, mengirim email ke administrator untuk memberi tahu bahwa ada kesalahan dalam pemrosesan pembayaran
      Mail::to('sendingemail117@gmail.com')->send(new MailOrderTransaction($order_id, $e->getMessage()));
      DB::rollBack();
    }
  }
}
