<?php

namespace App\Http\Controllers\API\Order;

use App\Http\Controllers\Controller;
use App\Services\Midtrans\CallbackService;
use Exception;
use Illuminate\Http\Request;

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

        try {
            $midtrans = new CallbackService($request->all());
            $midtrans->webHook();
            return response()->json([
                'status_code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ]);
        }
    }
}
