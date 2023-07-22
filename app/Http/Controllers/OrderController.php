<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        $orderId = $request->input('order_id');
        $transactionId = $request->input('transaction_id');
        $userName = $request->input('username');
        $sort_by = $request->input('sort_by') ? $request->input('sort_by') : 'created_at';
        $sort_by_option = $request->input('sort_by_option') ? $request->input('sort_by_option') : 'desc';
        $limit = $request->input('limit');

        $orders = Order::query()
            ->with(['detailorders', 'user'])
            ->when($userName, function ($query) use ($userName) {
                $query->whereHas('user', function ($builder) use ($userName) {
                    $builder->where('email', 'LIKE', '%' . $userName . '%')
                        ->orWhere('firstname', "LIKE", '%' . $userName . '%')
                        ->orWhere('middlename', 'LIKE', '%' . $userName . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $userName . '%');
                });
            })
            ->when($transactionId, function ($query) use ($transactionId) {
                $query->where('transaction_id', '=', $transactionId);
            })
            ->when($orderId, function ($query) use ($orderId) {
                $query->where('order_id', '=', $orderId);
            })
            ->when($sort_by, function ($query, $sort_by) use ($sort_by_option) {
                $query->orderBy($sort_by, $sort_by_option);
            })
            ->paginate($limit);


        $orders->appends([
            'order_id' => $orderId,
            'transaction_id' => $transactionId,
            'firstname' => $userName,
            'middlename' => $userName,
            'lastname' => $userName,
            'sort_by' => $sort_by,
            'sort_by_option' => $sort_by_option,
            'limit' => $limit
        ]);


        return response()->json([
            'status_code' => 200,
            'messages' => 'Success fetch orders',
            'data' => $orders
        ]);
    }
}
