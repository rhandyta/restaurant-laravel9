<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{

    public function index()
    {
        return view('report.index');
    }

    public function dailyReport(Request $request)
    {

        try {
            $dailyDate = $request->input('date') ?? date('Y-m-d');
            // $orders = Order::query()
            //     ->when($dailyDate, function ($q) use ($dailyDate) {
            //         $q->whereDate('created_at', '=', $dailyDate);
            //     })
            //     ->select('transaction_status', 'gross_amount')
            //     ->get();

            // $settlement = $orders->where('transaction_status', 'settlement');
            // $pending = $orders->where('transaction_status', 'pending');
            // $cancel = $orders->where('transaction_status', 'cancel');
            // $deny = $orders->where('transaction_status', 'deny');
            // $expire = $orders->where('transaction_status', 'expire');

            // $dataSum = [
            //     'settlement' => $settlement->sum('gross_amount'),
            //     'pending' => $pending->sum('gross_amount'),
            //     'cancel' => $cancel->sum('gross_amount'),
            //     'deny' => $deny->sum('gross_amount'),
            //     'expire' => $expire->sum('gross_amount'),
            // ];
            // $dataCount = [
            //     'settlement' => $settlement->count(),
            //     'pending' => $pending->count(), 
            //     'cancel' => $cancel->count(), 
            //     'deny' => $deny->count(),
            //     'expire' => $expire->count(),
            // ];
            $orders = Order::query()
                    ->when($dailyDate, function ($q) use ($dailyDate) {
                        $q->whereDate('created_at', '=', $dailyDate);
                    })
                    ->select('transaction_status', 'amount')
                    ->get();

                $data = $orders->groupBy('transaction_status')->map(function ($group) {
                    return [
                        'total_sum' => $group->sum('amount'),
                        'total_count' => $group->count(),
                    ];
                })
                ->toArray();

            return response()->json([
                'status_code' => Response::HTTP_OK,
                'messages' => 'fetch data successfully',
                'data' => $data,
                // 'data_sum' => $dataSum,
                // 'data_count' => $dataCount,
                'total_items' => $orders->count(),
                'total_sum' => $orders->sum('amount')
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function weeklyReport(Request $request)
    {
        // 
    }

    public function monthlyReport(Request $request)
    {
        // 
    }

    public function annualReport(Request $request)
    {
        // 
    }
}
