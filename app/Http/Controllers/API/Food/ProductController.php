<?php

namespace App\Http\Controllers\API\Food;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\FoodList;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function TopSelling()
    {
        $foods = DetailOrder::query()
            ->select('product_id', 'product')
            ->orderBy('product_id', 'desc')
            ->get();

        return response()->json([
            'data' => $foods
        ], 200);
    }

    public function RegularMenu()
    {
        $regularMenu = DetailOrder::query()
            ->with(['foodlist' => function ($query) {
                $query->select('id', 'food_name', 'food_description', 'price')->with(['foodimages' => function ($query) {
                    $query->select('id', 'food_list_id', 'public_id', 'image_url');
                }]);
            }])
            ->select('product_id', 'product', \DB::raw('FLOOR(SUM(rating) / NULLIF(COUNT(rating), 0)) as rating'), \DB::raw("COUNT(product_id) as total_product_id"))
            ->groupBy('product_id', 'product')
            ->limit(6)
            ->get();
        return response()->json([
            'data' => $regularMenu
        ], 200);
    }
}
