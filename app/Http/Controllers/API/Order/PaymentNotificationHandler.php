<?php

namespace App\Http\Controllers\API\Order;

use App\Http\Controllers\Controller;
use App\Mail\MailOrderTransaction;
use App\Mail\MailTransaction;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class PaymentNotificationHandler extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        DB::beginTransaction();
        try {
            $requestTransactionStatus = Http::withHeaders([
                'Accept' => 'application/json',
                "Content-Type" => 'application/json',
                "Authorization" => 'Basic ' . base64_encode(config('midtrans.server_key')) . ":"
            ])->get('https://api.sandbox.midtrans.com/v2/' . $request->input('order_id') . '/status')->object();

            $transaction = $requestTransactionStatus->transaction_status;
            $type = $requestTransactionStatus->payment_type;
            $order_id = $requestTransactionStatus->order_id;
            $transactionUpdate = Order::query()
                ->with(['user', 'detailorders' => function ($q) {
                    $q->with(['foodlist' => function ($q) {
                        $q->with('foodimages');
                    }]);
                }])
                ->where('order_id', '=', $order_id)->first();
            if ($transaction == 'settlement') {
                $transactionUpdate->update([
                    'transaction_status' => $request->input('transaction_status'),
                    'transaction_message' => "Transaction order: " . $order_id . " successfully transfered using " . $type,
                    'transaction_code' => $request->input('status_code'),
                ]);
                Mail::to($transactionUpdate->user->email)->send(new MailTransaction($transactionUpdate));
            } else if ($transaction == 'pending') {
                $transactionUpdate->update([
                    'transaction_status' => $request->input('transaction_status'),
                    'transaction_message' => "Waiting customer to finish transaction order: " . $order_id . " using " . $type,
                    'transaction_code' => $request->input('status_code'),
                ]);
                Mail::to($transactionUpdate->user->email)->send(new MailOrderTransaction($transactionUpdate));
            } else if ($transaction == 'deny') {
                $transactionUpdate->update([
                    'transaction_status' => $request->input('transaction_status'),
                    'transaction_message' => "Payment using " . $type . " for transaction order: " . $order_id . " is denied.",
                    'transaction_code' => $request->input('status_code'),
                ]);
                Mail::to($transactionUpdate->user->email)->send(new MailOrderTransaction($transactionUpdate));
            } else if ($transaction == 'expire') {
                $transactionUpdate->update([
                    'transaction_status' => $request->input('transaction_status'),
                    'transaction_message' => "Payment using " . $type . " for transaction order: " . $order_id . " is expired.",
                    'transaction_code' => $request->input('status_code'),
                ]);
                Mail::to($transactionUpdate->user->email)->send(new MailOrderTransaction($transactionUpdate));
            } else if ($transaction == 'cancel') {
                $transactionUpdate->update([
                    'transaction_status' => $request->input('transaction_status'),
                    'transaction_message' => "Payment using " . $type . " for transaction order: " . $order_id . " is canceled.",
                    'transaction_code' => $request->input('status_code'),
                ]);
                Mail::to($transactionUpdate->user->email)->send(new MailOrderTransaction($transactionUpdate));
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ]);
        }
    }
}
