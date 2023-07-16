<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        $transaction = Order::query()
            ->where('user_id', '=', $auth->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json([
            'status_code' => 200,
            'data' => $transaction
        ], 200);
    }

    public function detailTransaction(Request $request)
    {
        // 
    }
}
