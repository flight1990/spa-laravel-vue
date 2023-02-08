<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PassportController;
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

Route::controller(PassportController::class)->group(function () {
    Route::get('user', 'user')->middleware('auth:api');
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:api');
});

Route::controller(UserController::class)->prefix('users')->middleware('auth:api')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{user}', 'edit');
    Route::put('/{user}', 'update');
});
