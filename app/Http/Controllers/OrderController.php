<?php

namespace App\Http\Controllers;

use App\Models\FoodList;
use App\Models\Order;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    public function index(Request $request)
    {

        $orderId = $request->input('order_id');
        $transactionId = $request->input('transaction_id');
        $search = $request->input('search');
        $limit = $request->input('limit') ? $request->input('limit') : 15;

        $orders = Order::query()
            ->with(['user'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($builder) use ($search) {
                    $builder->where('email', 'LIKE', '%' . $search . '%')
                        ->orWhere('firstname', "LIKE", '%' . $search . '%')
                        ->orWhere('middlename', 'LIKE', '%' . $search . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search . '%');
                });
            })
            ->when($transactionId, function ($query) use ($transactionId) {
                $query->where('transaction_id', '=', $transactionId);
            })
            ->when($orderId, function ($query) use ($orderId) {
                $query->where('order_id', '=', $orderId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
        $products = FoodList::select('id', 'food_name', 'price')->get();

        $orders->appends([
            'order_id' => $orderId,
            'transaction_id' => $transactionId,
            'firstname' => $search,
            'middlename' => $search,
            'lastname' => $search,
            'limit' => $limit
        ]);

        return view('orders.index', compact('orders', 'products'));
    }


    public function store()
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
