<?php

namespace App\Http\Controllers\API\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $transactionUpdate = Order::where('order_id', '=', $request->order_id)->first();
            $transactionUpdate->update([
                'transaction_status' => $request->input('transaction_status'),
                'transaction_message' => $request->input('status_message'),
                'transaction_code' => $request->input('status_code'),
            ]);
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
