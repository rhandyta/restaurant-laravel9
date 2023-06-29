<?php

namespace App\Http\Controllers\API\Order;

use App\Http\Controllers\Controller;
use App\Mail\MailOrderTransaction;
use App\Mail\MailTransaction;
use App\Models\Order;
use App\Services\Midtrans\CallbackService;
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

        try {
            $midtrans = new CallbackService($request->all());
            $midtrans->webHook();

            return response()->json([
                'status_code' => 200,
                'messages' => 'Payment Success'
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ]);
        }
    }
}
