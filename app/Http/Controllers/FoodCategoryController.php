<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodCategoryStoreRequest;
use App\Http\Requests\FoodCategoryUpdateRequest;
use App\Models\FoodCategory;

class FoodCategoryController extends Controller
{
    public function index()
    {
        $foodCategories = FoodCategory::select('id', 'category_name', 'category_description')->paginate(25);
        return view('foodmanagement.index', compact('foodCategories'));
    }

    public function store(FoodCategoryStoreRequest $request)
    {
        // 
    }

    public function show($id)
    {
        // 
    }

    public function update($id, FoodCategoryUpdateRequest $request)
    {
        // 
    }

    public function destroy($id)
    {
        // 
    }
}
