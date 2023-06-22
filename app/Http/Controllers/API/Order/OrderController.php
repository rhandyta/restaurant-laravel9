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

            $transaction = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                "payment_type" => $request->input('payment_type'),
                'bank_transfer' => ['bank' => $request->input('bank')],
                // 'item_details' => $detailOrders
            ];

            $midtrans = new CreateSnapTokenService($transaction);
            $response = $midtrans->getSnapToken();


            $order = [
                'order_id' => $orderId,
                'user_id' => $auth->id,
                "transaction_id" => $response->transaction_id,
                "gross_amount" => $grossAmount,
                "amount" => $amount,
                "payment_type" => $request->input("payment_type"),
                "transaction_status" => $response->transaction_status,
                "transaction_code" => $response->status_code,
                "transaction_message" => $response->status_message,
                "signature_key" => hash('sha512', $orderId . $response->status_code . $response->gross_amount . config('midtrans.server_key')),
                "bank" => $request->input("bank"),
                "va_number" => $response->va_numbers[0]->va_number,
                "notes" => $request->input('notes'),
                "discount" => $request->input('discount')
            ];


            $order = Order::create($order);
            DB::commit();
            return response()->json(
                [
                    'user' => $auth,
                    'data' => ['order' => $order, 'detail_order' => $detailOrders],
                    'status_code' => 201,
                    'messages' => $order["transaction_message"]
                ],
                201
            );
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
