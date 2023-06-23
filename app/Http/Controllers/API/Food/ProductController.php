<?php

namespace App\Http\Controllers\API\Food;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use Exception;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function topSelling()
    {
        try {
            $foods = DetailOrder::query()
                ->with(['foodlist' => function ($query) {
                    $query->select('id', 'food_name')->with(['foodimages' => function ($query) {
                        $query->select('id', 'food_list_id', 'image_url');
                    }]);
                }])
                ->select('product_id', \DB::raw('COUNT(product_id) as total'))
                ->groupBy('product_id')
                ->orderBy('total', 'desc')
                ->take(3)
                ->get();

            return response()->json([
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
