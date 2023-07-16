<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Food\ProductController;
use App\Http\Controllers\API\Cart\CartController;
use App\Http\Controllers\API\Order\OrderController;
use App\Http\Controllers\API\Order\PaymentNotificationHandler;
use App\Http\Controllers\API\Transaction\TransactionController;
use App\Http\Controllers\API\Utils\UtilsController;
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
    Route::post('/{id}/increment', [CartController::class, 'increment']);
    Route::post('/{id}/decrement', [CartController::class, 'decrement']);
});

// Utils
Route::group(['prefix' => 'utils', 'middleware' => 'auth:sanctum'], function () {
    Route::get('tables', [UtilsController::class, 'getTableCategories']);
    Route::get('table', [UtilsController::class, 'getTables']);
    Route::get('bank-transfer', [UtilsController::class, 'getBankTransfer']);
    Route::get('e-wallet', [UtilsController::class, 'getEwallet']);
});

// Checkout
Route::group(['prefix' => 'order', 'middleware' => 'auth:sanctum'], function () {
    Route::post('checkout', OrderController::class);
});

// Webhook
Route::post('payment-notification-handler', PaymentNotificationHandler::class);

// Transaction
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'transaction'], function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::get('/search', [TransactionController::class, 'detailTransaction']);
});

// LandingPage
Route::get('top-selling', [ProductController::class, 'topSelling']);
Route::get('regular-menu', [ProductController::class, 'regularMenu']);
