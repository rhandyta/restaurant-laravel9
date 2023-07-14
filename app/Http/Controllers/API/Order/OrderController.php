<?php

namespace App\Http\Controllers\API\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Order\OrderRequest;
use App\Mail\MailOrderTransaction;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Services\Midtrans\CreateSnapTokenService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        try {
            DB::beginTransaction();
            $auth = Auth::user();
            $orderId = "OR" . date('dmy')  . \Str::random(6);

            $detailOrders = $request->input('detail_orders');
            $grossAmount = 0;
            $amount = 0;
         
            foreach ($detailOrders as $detail) {
                $detail['order_id'] = $orderId;
                $detail["subtotal"] = $detail['quantity'] * $detail['unit_price'];
                // $detail["total_price"] = $detail['subtotal'] - $detail['discount'];
                $detail["total_price"] = $detail['subtotal'] - 0;
                $grossAmount = $grossAmount + $detail['total_price'];
                $amount = $amount + $detail['subtotal'];
                $detail['product'] = $detail['food_name'];
                DetailOrder::create($detail);
            }
            $transaction = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount + ($grossAmount * 0.11),
                ],
                "payment_type" => $request->input('payment_type'),
                'bank_transfer' => ['bank' => $request->input('bank')],
                // 'item_details' => $detailOrders
            ];
            
            $midtrans = new CreateSnapTokenService($transaction);
            $response = $midtrans->getSnapToken();
            $createOrder = [
                'order_id' => $orderId,
                'user_id' => $auth->id,
                "transaction_id" => $response->transaction_id,
                "gross_amount" => $response->gross_amount,
                "amount" => $amount,
                "payment_type" => $request->input("payment_type"),
                "transaction_status" => $response->transaction_status,
                "transaction_code" => $response->status_code,
                "transaction_message" => $response->status_message,
                "signature_key" => hash('sha512', $orderId . $response->status_code . $response->gross_amount . config('midtrans.server_key')),
                "bank" => $request->input("bank"),
                "va_number" => $response->va_numbers[0]->va_number,
                "notes" => $request->input('notes'),
                "discount" => $request->input('discount') ? $request->input('discount') : null,
                'information_table' => $request->input('tables') . ' ' . $request->input('table')
            ];


            $order = Order::create($createOrder);
            Mail::to($auth->email)->send(new MailOrderTransaction($auth, $order, $detailOrders));
            DB::commit();
            return response()->json(
                [
                    'user' => $auth,
                    'data' => ['order' => $order, 'detail_order' => $detailOrders, 'user' => $auth],
                    'status_code' => Response::HTTP_CREATED,
                    'messages' => $order["transaction_message"]
                ],
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            return response()->json([
                    'messages' => $e->getMessage(),
                    'status_code' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
