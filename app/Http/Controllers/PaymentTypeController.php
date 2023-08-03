<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentTypeStoreRequest;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $types = PaymentType::query()
        ->paginate(25);


        return view('payment-type.index', compact('types'));
    }
    public function store(PaymentTypeStoreRequest $request)
    {
        \DB::beginTransaction();
        $payment_type = \Str::replace(' ', "_", $request->input('payment_type'));

        try {
            PaymentType::create([
                'payment_type' => $payment_type,
                'status' => $request->input('status'),
            ]);
        \DB::commit();
        return response()->json([
            'status_code' => 201,
            'messages' => 'Data has been created'
        ], 200);
        } catch(\Exception $e) {
            \DB::rollback();
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function show(PaymentType $type) {
        try {
            return response()->json([
                'status_code' => 200,
                'messages' => 'fetch success',
                'data' => $type
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }


    public function update(PaymentTypeStoreRequest $request)
    {
        \DB::beginTransaction();
        $payment_type = \Str::replace(' ', "_", $request->input('payment_type'));
        try {
            PaymentType::findOrFail($request->input('id'))->update([
                'payment_type' => $payment_type,
                'status' => $request->input('status'),
            ]);
        \DB::commit();
        return response()->json([
            'status_code' => 200,
            'messages' => 'Data has been updated'
        ], 200);
        } catch(\Exception $e) {
            \DB::rollback();
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
