<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\Cashier\DashbordController as CashierDashboard;
use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\FoodListController;
use App\Http\Controllers\InformationTableController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboard;
use App\Http\Controllers\Manager\MenuManagementController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TableCategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// auth start

Route::get('/', function () {
    echo 'test';
});

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', [LoginController::class, 'index'])->name('login.index');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});
// auth end


// Manager start
Route::group(['middleware' => ['isManager', 'isMenu'], 'prefix' => 'manager'], function () {
    Route::get('/', [ManagerDashboard::class, 'index'])->name('manager.index');

    // Menu Management
    Route::get('menu-managements', [MenuManagementController::class, 'index'])->name('menumanagement.index');
    Route::post('menu-managements/label', [MenuManagementController::class, 'handleLabelMenu'])->name('menumanagement.labelMenu');
    Route::post('menu-managements/menu', [MenuManagementController::class, 'handleMenu'])->name('menumanagement.menu');
    Route::post('menu-managements/submenu', [MenuManagementController::class, 'handleSubMenu'])->name('menumanagement.submenu');

    // Payment Method
    Route::resource('payment/types', PaymentTypeController::class, ['except' => ['edit', 'create']]);
    Route::resource('payment/bank', BankController::class, ['except' => ['edit', 'create']]);

    // Table Management
    Route::resource('tables/categories-tables', TableCategoryController::class, ['except' => ['edit', 'create']]);
    Route::resource('tables/information-tables', InformationTableController::class, ['except' => ['edit', 'create']]);

    // Food Management
    Route::resource('food-managements/food-categories', FoodCategoryController::class, ['except' => 'edit', 'create']);
    Route::resource('food-managements/food', FoodListController::class, ['except' => ['create', 'edit', 'update']]);
    Route::post('food-managements/food/{id}', [FoodListController::class, 'update'])->name('food.update');

    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders-manager.index');
    Route::get('orders/{order:order_id}/show', [OrderController::class, 'show'])->name('orders-cashier.show');

    // Receipt
    Route::get('receipt', [ReceiptController::class, 'receipt']);

    // Reports
    Route::get('financial-reports', [ReportController::class, 'index'])->name('report.index');
    Route::post('financial-reports/daily-reports', [ReportController::class, 'dailyReport'])->name('report-manager.daily');
    Route::post('financial-reports/weekly-reports', [ReportController::class, 'weeklyReport'])->name('report-manager.weekly');
    Route::post('financial-reports/monthly-reports', [ReportController::class, 'monthlyReport'])->name('report-manager.monthly');

    Route::get('logout', function () {
        Auth::logout();
        return redirect()->route('login.index');
    })->name('manager.logout');
});
// Manager end


// Cashier Start
Route::group(['middleware' => ['isCashier', 'isMenu'], 'prefix' => 'cashier'], function () {
    Route::get('/', [CashierDashboard::class, 'index'])->name('cashier.index');

    // Payment Method
    Route::resource('payment/types', PaymentTypeController::class, ['except' => ['edit', 'create']]);
    Route::resource('payment/bank', BankController::class, ['except' => ['edit', 'create']]);

    // Table Management
    Route::resource('tables/categories-tables', TableCategoryController::class, ['except' => ['edit', 'create']]);
    Route::resource('tables/information-tables', InformationTableController::class, ['except' => ['edit', 'create']]);

    // Food Management
    Route::resource('food-managements/food-categories', FoodCategoryController::class, ['except' => 'edit', 'create']);
    Route::resource('food-managements/food', FoodListController::class, ['except' => ['create', 'edit', 'update']]);
    Route::post('food-managements/food/{id}', [FoodListController::class, 'update'])->name('food.update');

    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders-cashier.index');;
    Route::post('orders', [OrderController::class, 'store'])->name('orders-cashier.store');
    Route::get('orders/{order:order_id}/show', [OrderController::class, 'show'])->name('orders-cashier.show');
    Route::patch('orders/{order:order_id}/update', [OrderController::class, 'update'])->name('orders-cashier.update');

    // Reports
    Route::get('financial-reports', [ReportController::class, 'index'])->name('report.index');
    Route::post('financial-reports/daily-reports', [ReportController::class, 'dailyReport'])->name('report.daily');
    Route::post('financial-reports/weekly-reports', [ReportController::class, 'weeklyReport'])->name('report.weekly');
    Route::post('financial-reports/monthly-reports', [ReportController::class, 'monthlyReport'])->name('report.monthly');

    // Receipt
    Route::get('receipt', [ReceiptController::class, 'receipt'])->name('receipt');

    Route::get('logout', function () {
        Auth::logout();
        return redirect()->route('login.index');
    })->name('cashier.logout');
});

// Cashier end

