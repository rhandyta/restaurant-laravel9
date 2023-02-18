<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodCategoryStoreRequest;
use App\Http\Requests\FoodCategoryUpdateRequest;
use App\Models\FoodCategory;
use Exception;

class FoodCategoryController extends Controller
{
    public function index()
    {
        $foodCategories = FoodCategory::select('id', 'category_name', 'category_description')
            ->orderBy('id', 'desc')
            ->paginate(25);
        return view('foodmanagement.index', compact('foodCategories'));
    }

    public function store(FoodCategoryStoreRequest $request)
    {
        try {
            FoodCategory::create([
                'category_name' => ucwords($request->input('category_name')),
                'category_description' => ucfirst($request->input('category_description'))
            ]);

            return response()->json([
                'status_code' => 201,
                'message' => 'Data has been created'
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
            $foodCategory = FoodCategory::findOrFail($id);
            return response()->json([
                'status_code' => 200,
                'messages' => "Fetch data success",
                'results' => $foodCategory
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function update($id, FoodCategoryUpdateRequest $request)
    {
        try {
            FoodCategory::where('id', '=', $id)
                ->update([
                    'category_name' => $request->input('category_name'),
                    'category_description' => $request->input('category_description')
                ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Data has been updated'
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
            FoodCategory::findOrFail($id)->delete();
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
