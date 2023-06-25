<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Food\ProductController;
use App\Http\Controllers\API\Cart\CartController;
use App\Http\Controllers\API\Order\OrderController;
use App\Http\Controllers\API\Order\PaymentNotificationHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// auth
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);
});

// Cart
Route::group(['prefix' => 'cart', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/', [CartController::class, 'store']);
    Route::post('destroy', [CartController::class, 'destroy']);
});

// Checkout
Route::group(['prefix' => 'order', 'middleware' => 'auth:sanctum'], function () {
    Route::post('checkout', OrderController::class);
});

// Webhook
Route::post('payment-notification-handler', PaymentNotificationHandler::class);


Route::get('top-selling', [ProductController::class, 'topSelling']);
Route::get('regular-menu', [ProductController::class, 'regularMenu']);
