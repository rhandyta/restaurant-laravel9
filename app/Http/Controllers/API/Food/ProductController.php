<?php

namespace App\Http\Controllers\API\Food;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\FoodList;
use Exception;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function topSelling()
    {
        try {

            $foods = FoodList::query()
                ->with(['foodimages' => function ($query) {
                    $query->select('id', 'food_list_id', 'image_url');
                }])
                ->take(3)
                ->get();

            return response()->json([
                'status_code' => 200,
                'messages' => "Data successfully fetch",
                'data' => $foods
            ], 200);
        } catch (Exception  $e) {
            return response()->json([
                'data' => [
                    'messages' => $e->getMessage(),
                    'status_code' => Response::HTTP_BAD_REQUEST
                ]
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function regularMenu()
    {
        try {

            $regularMenu = FoodList::query()
                ->with(['detailorders' => function ($query) {
                    $query->select('product_id', 'product', \DB::raw('FLOOR(SUM(rating) / NULLIF(COUNT(rating), 0)) as rating'), \DB::raw('COUNT(product_id) as total_product_id'))
                        ->groupBy('product_id', 'product');
                }, 'foodimages' => function ($query) {
                    $query->select('id', 'food_list_id', 'image_url');
                }])
                ->take(6)
                ->get();

            return response()->json([
                'data' => $regularMenu,
                'status_code' => 200,
                'messages' => "Data successfully fetch"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => [
                    'messages' => $e->getMessage(),
                    'status_code' => Response::HTTP_BAD_REQUEST
                ]
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
