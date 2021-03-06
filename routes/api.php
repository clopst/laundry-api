<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
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

Route::group(
    [
        'prefix' => 'auth',
        'as' => 'auth.',
        'middleware' => ['auth:sanctum']
    ],
    function () {
        Route::get('', [AuthController::class, 'getUser'])
            ->name('index');
        Route::post('update-profile', [AuthController::class, 'updateProfile'])
            ->name('update-profile');
        Route::post('change-password', [AuthController::class, 'changePassword'])
            ->name('change-password');
    }
);

Route::group(
    [
        'prefix' => 'users',
        'as' => 'users.',
        'middleware' => ['auth:sanctum']
    ],
    function () {
        Route::get('', [UserController::class, 'index'])
            ->name('index');
        Route::post('', [UserController::class, 'store'])
            ->name('store');
        Route::get('{user}', [UserController::class, 'show'])
            ->name('show');
        Route::post('{user}', [UserController::class, 'update'])
            ->name('update');
        Route::post('{user}/change-password', [UserController::class, 'changePassword'])
            ->name('change-password');
        Route::delete('{user}', [UserController::class, 'destroy'])
            ->name('destroy');
    }
);

Route::group(
    [
        'prefix' => 'customers',
        'as' => 'customers.',
        'middleware' => ['auth:sanctum']
    ],
    function () {
        Route::get('', [CustomerController::class, 'index'])
            ->name('index');
        Route::post('', [CustomerController::class, 'store'])
            ->name('store');
        Route::get('{customer}', [CustomerController::class, 'show'])
            ->name('show');
        Route::put('{customer}', [CustomerController::class, 'update'])
            ->name('update');
        Route::delete('{customer}', [CustomerController::class, 'destroy'])
            ->name('destroy');
    }
);

Route::group(
    [
        'prefix' => 'outlets',
        'as' => 'outlets.',
        'middleware' => ['auth:sanctum']
    ],
    function () {
        Route::get('', [OutletController::class, 'index'])
            ->name('index');
        Route::post('', [OutletController::class, 'store'])
            ->name('store');
        Route::get('dropdowns', [OutletController::class, 'getDropdowns'])
            ->name('dropdowns');
        Route::get('{outlet}', [OutletController::class, 'show'])
            ->name('show');
        Route::put('{outlet}', [OutletController::class, 'update'])
            ->name('update');
        Route::delete('{outlet}', [OutletController::class, 'destroy'])
            ->name('destroy');
    }
);

Route::group(
    [
        'prefix' => 'products',
        'as' => 'products.',
        'middleware' => ['auth:sanctum']
    ],
    function () {
        Route::get('', [ProductController::class, 'index'])
            ->name('index');
        Route::post('', [ProductController::class, 'store'])
            ->name('store');
        Route::get('dropdowns', [ProductController::class, 'getDropdowns'])
            ->name('dropdowns');
        Route::get('{product}', [ProductController::class, 'show'])
            ->name('show');
        Route::put('{product}', [ProductController::class, 'update'])
            ->name('update');
        Route::delete('{product}', [ProductController::class, 'destroy'])
            ->name('destroy');
    }
);

Route::group(
    [
        'prefix' => 'transactions',
        'as' => 'transactions.',
        // 'middleware' => ['auth:sanctum']
    ],
    function () {
        Route::get('', [TransactionController::class, 'index'])
            ->name('index');
        Route::post('', [TransactionController::class, 'store'])
            ->name('store');
        Route::get('dropdowns', [TransactionController::class, 'getDropdowns'])
            ->name('dropdowns');
        Route::get('export', [TransactionController::class, 'export'])
            ->name('export');
        Route::get('{transaction}', [TransactionController::class, 'show'])
            ->name('show');
        Route::put('{transaction}', [TransactionController::class, 'update'])
            ->name('update');
        Route::post('{id}/change-status', [TransactionController::class, 'changeStatus'])
            ->name('change-status');
        Route::delete('{transaction}', [TransactionController::class, 'destroy'])
            ->name('destroy');
    }
);

Route::group(
    [
        'prefix' => 'dashboard',
        'as' => 'dashboard.',
        'middleware' => ['auth:sanctum']
    ],
    function () {
        Route::get('', [DashboardController::class, 'index'])
            ->name('index');
    }
);
