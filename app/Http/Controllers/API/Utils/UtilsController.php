<?php

namespace App\Http\Controllers\API\Utils;

use App\Http\Controllers\Controller;
use App\Models\InformationTable;
use App\Models\TableCategory;
use Illuminate\Http\Response;

class UtilsController extends Controller
{

    public function getBankTransfer()
    {
        // 
    }

    public function getEwallet()
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

    public function getTables($id)
    {
        try {
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
