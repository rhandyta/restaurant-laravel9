<?php

namespace App\Http\Controllers\API\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Order\OrderRequest;
use App\Models\DetailOrder;
use App\Models\Order;
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
        $auth = Auth::user();
        $order = [
            'order_id' => "OR" . date('dmy')  . \Str::random(6),
            'user_id' => $auth->id,
            "transaction_id" => null,
            "gross_amount" => $request->input('gross_amount'),
            "payment_type" => $request->input('payment_type'),
            "transaction_status" => null,
            "bank" => null,
            "va_number" => null,
            "notes" => $request->input('notes'),
            "discount" => $request->input('discount')
        ];
        $detailOrders = $request->input('detail_orders');
        $order = Order::create($order);
        foreach ($detailOrders as $detail) {
            $detail['order_id'] = $order->order_id;
            $detail["subtotal"] = $detail['quantity'] * $detail['unit_price'];
            $detail["total_price"] = $detail['subtotal'] - $detail['discount'];
            DetailOrder::create($detail);
        }


        return response()->json(
            [
                'user' => $auth,
                'data' => ['order' => $order, 'detail_order' => $detailOrders],
                'code' => 201,
                'messages' => 'create order has been created'
            ],
            201
        );
    }
}
