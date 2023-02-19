<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodListStoreRequest;
use App\Http\Requests\FoodListUpdateRequest;
use App\Models\FoodList;
use Illuminate\Support\Facades\Storage;

class FoodListController extends Controller
{
    public function index()
    {
        $response = cloudinary()->search()->expression("folder:YelpCamp/*")->execute();
        $datas = [];

        foreach ($response["resources"] as $resource) {
            array_push($datas, $resource["public_id"]);
        }

        $storage = $response["resources"][0];
        dd($storage);
        $foodLists = FoodList::with('foodcategory')
            ->orderBy('id', 'desc')
            ->paginate(25);
        return view('foodmanagement.food', compact('foodLists'));
    }

    public function store(FoodListStoreRequest $request)
    {
        // 
    }

    public function update($id, FoodListUpdateRequest $request)
    {
        // 
    }

    public function destroy($id)
    {
        // 
    }
}
