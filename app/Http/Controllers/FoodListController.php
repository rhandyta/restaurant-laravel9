<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodListStoreRequest;
use App\Http\Requests\FoodListUpdateRequest;
use App\Models\FoodCategory;
use App\Models\FoodList;
use Exception;

class FoodListController extends Controller
{
    public function index()
    {
        $foodCategories = FoodCategory::select('id', 'category_name')
            ->orderBy('id', 'desc')
            ->get();
        $foodLists = FoodList::with(['foodcategory', 'foodimages'])
            ->orderBy('id', 'desc')
            ->paginate(25);
        return view('foodmanagement.food', compact('foodLists', 'foodCategories'));
    }

    public function store(FoodListStoreRequest $request)
    {
        try {
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = cloudinary()->uploadApi()->upload($images->getRealPath(), [
                        'folder' => 'restaurant/food',
                    ]);
                    $uploadedImages[] = [
                        'image_url' => $path['secure_url'],
                        'public_id' => $path['public_id']
                    ];
                }
            }
            $food = FoodList::create([
                'food_category_id' => (int)$request->input('food_category_id'),
                'food_name' => $request->input('food_name'),
                'food_description' => $request->input('food_description'),
                'price' => (int)$request->input('price'),
            ]);
            $food->foodimages()->createMany($uploadedImages);

            return response()->json([
                'status_code' => 201,
                'message' => "Data has been created"
            ]);
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
            $food = FoodList::with('foodimages')
                ->findOrFail($id);
            return response()->json([
                'status_code' => 200,
                'message' => "Fetch success",
                'results' => $food
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage()
            ]);
        }
    }

    public function update($id, FoodListUpdateRequest $request)
    {
        try {
            if (!$request->hasFile('images')) {
                FoodList::where('id', '=', $id)->update([
                    'food_category_id' => (int)$request->input('food_category_id'),
                    'food_name' => $request->input('food_name'),
                    'food_description' => $request->input('food_description'),
                    'price' => $request->input('price'),
                ]);
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Data has been updated',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'messages' => $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        // 
    }
}
