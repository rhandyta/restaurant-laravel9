<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformationTableStoreRequest;
use App\Http\Requests\InformationTableUpdateRequest;
use App\Models\InformationTable;
use App\Models\TableCategory;
use Exception;

class InformationTableController extends Controller
{
    public function index()
    {
        $categoriesTables = TableCategory::select('id', 'category', 'status')->get();
        $informationTables = InformationTable::with('tablecategory')
            ->orderBy('id', 'desc')
            ->paginate(25);
        return view('tables.informationtable', compact('informationTables', 'categoriesTables'));
    }

    public function store(InformationTableStoreRequest $request)
    {
        try {
            $informationTable = InformationTable::create([
                'category_table_id' => (int)$request->input('category_table_id'),
                'seating_capacity' => (int)$request->input('seating_capacity'),
                'available' => $request->input('available'),
                'location' => $request->input('location'),
            ]);
            return response()->json([
                'status_code' => 201,
                'message' => 'Data has been created',
                'results' => $informationTable
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $informationTable = InformationTable::findOrFail($id);
            return response()->json([
                'status_code' => 200,
                'message' => 'fetch data success',
                'results' => $informationTable
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function update($id, InformationTableUpdateRequest $request)
    {
        try {
            InformationTable::where('id', '=', $id)
                ->update([
                    'category_table_id' => (int)$request->input('category_table_id'),
                    'seating_capacity' => (int)$request->input('seating_capacity'),
                    'available' => $request->input('available'),
                    'location' => $request->input('location'),
                ]);
            return response()->json([
                'status_code' => 200,
                'message' => "Data has been update"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            InformationTable::findOrFail($id)->delete();
            return response()->json([
                'status_code' => 200,
                'message' => 'Data has been deleted'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
