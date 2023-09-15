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
        try {
            $startDate = $request->input('startDate') ?? date('Y-m-d');
            $endDate = $request->input('endDate') ?? date('Y-m-d', strtotime('-7 days'));

            $orders = Order::query()
                    ->when($startDate, function ($q) use ($startDate, $endDate) {
                        $q->whereDate('created_at', '>=', $startDate)
                        ->whereDate('created_at', '<=', $endDate);
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

    public function monthlyReport(Request $request)
    {
        try {
            $startDate = $request->input('startDate') ?? date('Y-m-d');
            $endDate = $request->input('endDate') ?? date('Y-m-d', strtotime('-30 days'));

            $orders = Order::query()
                    ->when($startDate, function ($q) use ($startDate, $endDate) {
                        $q->whereDate('created_at', '>=', $startDate)
                        ->whereDate('created_at', '<=', $endDate);
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

    public function annualReport(Request $request)
    {
        // 
    }
}
