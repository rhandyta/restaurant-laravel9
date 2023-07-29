<?php

namespace App\Http\Controllers\API\Utils;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\InformationTable;
use App\Models\TableCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UtilsController extends Controller
{

    public function getProducts()
    {
        try {
            $foods = FoodCategory::query()
                ->with(['foodlists'])
                ->get();

            return response()->json([
                'status_code' => 200,
                'messages' => 'Fetch success',
                'data' => $foods
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function getBankTransfer()
    {
        //
    }


    public function getTableCategories()
    {
        try {
            $tables = TableCategory::query()
                ->where('status', '=', "active")
                ->get();

            return response()->json([
                'status_code' => Response::HTTP_OK,
                'messages' => "Fetch data successfully",
                'data' => $tables
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function getTables(Request $request)
    {
        try {
            $id = $request->query('id');
            $tables = InformationTable::query()
                ->where('category_table_id', '=', $id)
                ->where('available', '=', 'available')
                ->get();

            return response()->json([
                'status_code' => Response::HTTP_OK,
                'messages' => "Fetch successfully",
                'data' => $tables
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ]);
        }
    }
}
