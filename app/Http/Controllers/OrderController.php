<?php

namespace App\Http\Controllers;

use App\Jobs\MailOrderJob;
use App\Models\DetailOrder;
use App\Models\FoodList;
use App\Models\Order;
use App\Models\PaymentType;
use App\Models\TableCategory;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Services\WebSocket\TransactionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use stdClass;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $limit = $request->input('limit') ? $request->input('limit') : 15;
        $page = $request->input('page') ?: 1;
        $orders = Order::query()
            ->with(['user'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('transaction_id', '=', $search)
                        ->orWhere('order_id', '=', $search);
                });
                $query->orWhereHas('user', function ($builder) use ($search) {
                    $builder->where('email', 'LIKE', '%' . $search . '%')
                        ->orWhere('firstname', 'LIKE', '%' . $search . '%')
                        ->orWhere('middlename', 'LIKE', '%' . $search . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
        $products = FoodList::select('id', 'food_name', 'price')->get();
        $tables = TableCategory::with(['informationtables' => function ($q) {
            $q->where('available', '=', 'available');
        }])
            ->where('status', '=', 'active')
            ->select('category', 'status', 'id')
            ->get();
        $paymenttypes = PaymentType::query()
                    ->with(['banks' => function ($q) {
                        $q->where('status', '=', 'available');
                    }])
                    ->where('status', '=', 'available')
                    ->get();

        $orders->appends([
            'search' => $search,
            'limit' => $limit,
            'page' => $page,
        ]);
        if ($page > $orders->lastPage()) {
            $page = 1;
            if (Auth::user()->roles == 'manager') {
                return redirect()->route('orders-manager.index', ['search' => $search, 'limit' => $limit, 'page' => $page]);
            }
            return redirect()->route('orders-cashier.index', ['search' => $search, 'limit' => $limit, 'page' => $page]);
        }

        return view('orders.index', compact('orders', 'products', 'tables', 'paymenttypes'));
    }


    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $tables = TableCategory::findOrFail((int)$request->input('tables'));
            $auth = new stdClass;
            $auth->id = Auth::user()->id;
            $auth->email = $request->input('email');
            $auth->firstname = $request->input('firstname');
            $auth->telephone = $request->input('phone');
            $orderId = "OR" . date('dmy')  . \Str::random(6);
            $detailOrders = $request->input('detail_orders');
            $grossAmount = 0;
            $amount = 0;
            $details = [];
            foreach ($detailOrders as $detail) {
                $detail['price'] = $detail['unit_price'] + ($detail['unit_price'] * 0.11);
                $detail['quantity'] = $detail['quantity'];
                $detail['name'] = $detail['food_name'];
                $detail['id'] = $detail['id'];

                $detail['order_id'] = $orderId;
                $detail["subtotal"] = $detail['quantity'] * $detail['unit_price'];
                // $detail["total_price"] = $detail['subtotal'] - $detail['discount'];
                $detail["total_price"] = $detail['subtotal'] - 0;
                $grossAmount = $grossAmount + $detail['total_price'];
                $amount = $amount + $detail['subtotal'];
                $detail['product'] = $detail['food_name'];
                DetailOrder::create($detail);
                array_push($details, $detail);
            }

            if ($request->input('payment_type') == 'bank_transfer') {
                $transaction = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => $grossAmount + ($grossAmount * 0.11),
                    ],
                    "payment_type" => $request->input('payment_type'),
                    'bank_transfer' => ['bank' => $request->input('bank')],
                    'item_details' => $details,
                    'customer_details' => [
                        "first_name" => $auth->firstname,
                        "last_name" => '',
                        "email" => $auth->email,
                        "phone" => $auth->telephone,
                        "billing_address" => [
                            "first_name" => $auth->firstname,
                            "last_name" => '',
                            "email" => $auth->email,
                            "phone" => $auth->telephone,
                            "country_code" => "IDN"
                        ]
                    ]
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
                    'information_table' => $tables->category . ' ' . '-' . ' ' . $request->input('table'),
                    'name' => $auth->firstname,
                    'email' => $auth->email,
                    'telephone' => $auth->telephone
                ];
                $order = Order::create($createOrder);
            } else {
                $createOrder = new stdClass;
                $createOrder->order_id = $orderId;
                $createOrder->user_id = $auth->id;
                $createOrder->transaction_id = Uuid::uuid4();
                $createOrder->gross_amount = $grossAmount + ($grossAmount * 0.11);
                $createOrder->amount = $amount;
                $createOrder->payment_type = $request->input("payment_type");
                $createOrder->transaction_status = 'settlement';
                $createOrder->transaction_code = 201;
                $createOrder->transaction_message = 'Transaction order successfully';
                $createOrder->notes = $request->input('notes');
                $createOrder->discount = $request->input('discount') ? $request->input('discount') : null;
                $createOrder->information_table = $tables->category . ' ' . '-' . ' ' .  $request->input('table');
                $createOrder->user = $auth;
                $createOrder->detailOrders = DetailOrder::where('order_id', '=', $orderId)->with(['foodlist' => function ($q) {
                    $q->with('foodimages');
                }])->get();
                MailOrderJob::dispatch($createOrder, $auth, $createOrder->transaction_status)->afterCommit()->delay(now()->addSeconds(10));

                $order = Order::create([
                    'order_id' => $orderId,
                    'user_id' => $auth->id,
                    "transaction_id" => Uuid::uuid4(),
                    "gross_amount" => $grossAmount + ($grossAmount * 0.11),
                    "amount" => $amount,
                    "payment_type" => $request->input("payment_type"),
                    "transaction_status" => 'settlement',
                    "transaction_code" => 201,
                    "transaction_message" => 'Transaction order successfully',
                    "notes" => $request->input('notes'),
                    "discount" => $request->input('discount') ? $request->input('discount') : null,
                    'information_table' => $tables->category . ' ' . '-' . ' ' .  $request->input('table'),
                    'name' => $auth->firstname,
                    'email' => $auth->email,
                    'telephone' => $auth->telephone
                ]);
                $orderService = new TransactionService($order);
                $orderService->sendEventOrder();
            }


            \DB::commit();
            return response()->json(
                [
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

    public function show(Order $order)
    {
        $order->load('detailorders');
        return view('orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        try {
            if($request->filled('transaction_status')){
                $order->update([
                    'transaction_status' => $request->input('transaction_status'),
                    'transaction_code' => 200
                ]);            
                $orderService = new TransactionService($order);
                $orderService->sendEventOrder();
                $orderService->sendConfirmOrderToUser();
                return response()->json([
                    'status_code' => Response::HTTP_OK,
                    'messages' => 'transaction has been updated'
                ], Response::HTTP_OK);
            }
            return response()->json([
                'status_code' => Response::HTTP_BAD_REQUEST,
                'messages' => 'transaction status is not updated'
            ], Response::HTTP_BAD_REQUEST);
        } catch(\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy()
    {
        //
    }
}
