<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $auth = Auth::user();
            $carts = Cart::query()
                ->with(['user' => function($query) {
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
