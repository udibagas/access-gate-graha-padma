<?php

use App\Http\Controllers\AccessGateController;
use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
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

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::resource('user', UserController::class)->except(['create', 'edit']);
    Route::resource('accessGate', AccessGateController::class)->except(['create', 'edit']);
    Route::resource('camera', CameraController::class)->except(['create', 'edit']);
    Route::resource('member', MemberController::class)->except(['create', 'edit']);
});

Route::post('accessLog', [AccessLogController::class, 'store']);
