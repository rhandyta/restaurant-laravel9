<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryTableStoreRequest;
use App\Models\TableCategory;
use Exception;
use Illuminate\Http\Request;

class TableCategoryController extends Controller
{
    public function index()
    {
        $categorytables = TableCategory::orderBy('id', 'desc')->paginate(25);
        return view('tables.index', compact('categorytables'));
    }

    public function store(CategoryTableStoreRequest $request)
    {
        try {
            $categoryTable = TableCategory::create([
                'category' => $request->input('category'),
                'status' => $request->input('status') == null ? 'Deactive' : $request->input('status')
            ]);
            return response()->json([
                'status_code' => 201,
                'message' => 'Data has been added',
                'results' => $categoryTable
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
            $categoryTable = TableCategory::findOrFail($id);
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetch data success',
                'results' => $categoryTable
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $category = $request->input('category');
            $status = $request->input('status');
            TableCategory::where('id', '=', $id)
                ->update([
                    'category' => $category,
                    'status' => $status
                ]);
            return response()->json([
                'status_code' => 201,
                'message' => 'Data has been updated'
            ], 201);
        } catch (Exception $e) {
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
