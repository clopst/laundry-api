<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(
    [
        'prefix' => 'users',
        'as' => 'users.'
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
