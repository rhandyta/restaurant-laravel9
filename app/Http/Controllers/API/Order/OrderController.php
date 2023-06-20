<?php

namespace App\Http\Controllers\API\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Order\OrderRequest;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Services\Midtrans\CreateSnapTokenService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
            $auth = Auth::user();
            $orderId = "OR" . date('dmy')  . \Str::random(6);

            $detailOrders = $request->input('detail_orders');
            $grossAmount = 0;
            $amount = 0;
            foreach ($detailOrders as $detail) {
                $detail['order_id'] = $orderId;
                $detail["subtotal"] = $detail['quantity'] * $detail['unit_price'];
                $detail["total_price"] = $detail['subtotal'] - $detail['discount'];
                $grossAmount = $grossAmount + $detail['total_price'];
                $amount = $amount + $detail['subtotal'];
                DetailOrder::create($detail);
            }

            $order = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                "payment_type" => $request->input('payment_type'),
                'bank_transfer' => ['bank' => $request->input('bank')],
                // 'item_details' => $detailOrders
            ];
            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = false;
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = true;
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = true;

            $response = \Midtrans\CoreApi::charge($order);
            dd($response);

            // $order = [
            //     'order_id' => $orderId,
            //     'user_id' => $auth->id,
            //     "transaction_id" => $request->input("transaction_id"),
            //     "gross_amount" => $grossAmount,
            //     "amount" => $amount,
            //     "payment_type" => $request->input("payment_type"),
            //     "transaction_status" => $request->input("transaction_status"),
            //     "bank" => $request->input("bank"),
            //     "va_number" => $request->input("va_number"),
            //     "notes" => $request->input('notes'),
            //     "discount" => $request->input('discount')
            // ];

            // $order = Order::create($order);

            return response()->json(
                [
                    'user' => $auth,
                    'data' => ['order' => $order, 'detail_order' => $detailOrders],
                    'code' => 201,
                    'messages' => 'create order has been created'
                ],
                201
            );
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            if ($e->getCode() === 0) {
                return response()->json([
                    'code' => 400,
                    'message' => 'Bad request'
                ], 400);
            }
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
