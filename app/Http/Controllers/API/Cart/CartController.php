<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Cart\CartStoreRequest;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        try {
            $auth = Auth::user();
            $carts = Cart::query()
                ->with(['user' => function ($query) {
                    $query->select('id', 'email');
                }, 'product'])
                ->where('user_id', '=', $auth->id)
                ->get();
            return response()->json([
                'status_code' => Response::HTTP_OK,
                'messages' => 'Data successfully fetch',
                'data' => $carts
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => Response::HTTP_BAD_REQUEST
            ], $e->getCode());
        }
    }

    public function store(CartStoreRequest $request)
    {
        try {
            $auth = Auth::user();
            $exists = Cart::where('user_id', '=', $auth->id)->where('product_id', '=', $request->input('product_id'))->select('product_id')->first();

            if ($exists) {
                return response()->json([
                    'status_code' => Response::HTTP_CONFLICT,
                    'messages' => 'Data conflict'
                ], Response::HTTP_CONFLICT);
            }

            Cart::create([
                'user_id' => $auth->id,
                'product_id' => $request->input('product_id'),
                'quantity' => $request->input('quantity')
            ]);

            return response()->json([
                'status_code' => Response::HTTP_CREATED,
                'messages' => 'Data has been created'
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => Response::HTTP_BAD_REQUEST
            ], $e->getCode());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $auth = Auth::user();
            $carts = $request->input('id');
            Cart::where('user_id', '=', $auth->id)
                ->delete(collect($carts));

            return response()->json([
                'status_code' => Response::HTTP_NO_CONTENT,
                'messages' => 'Data has been deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => Response::HTTP_BAD_REQUEST
            ], $e->getCode());
        }
    }
}
