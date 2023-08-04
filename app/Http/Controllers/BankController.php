<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankStoreUpdateRequest;
use App\Models\Bank;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = Bank::query()
        ->with(['paymenttype'])
        ->paginate(25);
        $paymenttypes = PaymentType::query()
            ->where('status', '=', 'available')
            ->get();

        return view('bank.index', compact('banks', 'paymenttypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankStoreUpdateRequest $request)
    {
        \DB::beginTransaction();
        $name = \Str::replace(' ', "_", $request->input('name'));

        try {
            Bank::create([
                'payment_type_id' => (int)$request->input('payment_type_id'),
                'name' => $name,
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        try {
            return response()->json([
                'status_code' => 200,
                'messages' => 'fetch success',
                'data' => $bank
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(BankStoreUpdateRequest $request, Bank $bank)
    {
        \DB::beginTransaction();
        $name = \Str::replace(' ', "_", $request->input('name'));
        try {
            $bank->update([
                'name' => $name,
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        try {
            $bank->delete();
            return response()->json([
                'status_code' => 200,
                'message' => 'Data has been deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
