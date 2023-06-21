<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cashier\DashbordController as CashierDashboard;
use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\FoodListController;
use App\Http\Controllers\InformationTableController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboard;
use App\Http\Controllers\Manager\MenuManagementController;
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


    Route::get('menu-managements', [MenuManagementController::class, 'index'])->name('menumanagement.index');
    Route::post('menu-managements/label', [MenuManagementController::class, 'handleLabelMenu'])->name('menumanagement.labelMenu');
    Route::post('menu-managements/menu', [MenuManagementController::class, 'handleMenu'])->name('menumanagement.menu');
    Route::post('menu-managements/submenu', [MenuManagementController::class, 'handleSubMenu'])->name('menumanagement.submenu');

    Route::resource('tables/categories-tables', TableCategoryController::class, ['except' => ['edit', 'create']]);

    Route::resource('tables/information-tables', InformationTableController::class, ['except' => ['edit', 'create']]);

    Route::resource('food-managements/food-categories', FoodCategoryController::class, ['except' => 'edit', 'create']);

    Route::resource('food-managements/food', FoodListController::class, ['except' => ['create', 'edit', 'update']]);
    Route::post('food-managements/food/{id}', [FoodListController::class, 'update'])->name('food.update');

    Route::get('logout', function () {
        Auth::logout();
        return redirect()->route('login.index');
    })->name('manager.logout');
});
// Manager end


// Cashier Start
Route::group(['middleware' => ['isCashier', 'isMenu'], 'prefix' => 'cashier'], function () {
    Route::get('/', [CashierDashboard::class, 'index'])->name('cashier.index');

    Route::resource('tables/categories-tables', TableCategoryController::class, ['except' => ['edit', 'create']]);

    Route::resource('tables/information-tables', InformationTableController::class, ['except' => ['edit', 'create']]);

    Route::resource('food-managements/food-categories', FoodCategoryController::class, ['except' => 'edit', 'create']);

    Route::resource('food-managements/food', FoodListController::class, ['except' => ['create', 'edit', 'update']]);
    Route::post('food-managements/food/{id}', [FoodListController::class, 'update'])->name('food.update');

    Route::get('logout', function () {
        Auth::logout();
        return redirect()->route('login.index');
    })->name('cashier.logout');
});

// Cashier end