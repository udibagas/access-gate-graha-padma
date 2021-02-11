<?php

use App\Http\Controllers\AccessGateController;
use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SnapshotController;
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
Route::post('accessLog', [AccessLogController::class, 'store']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // AUTH RELATED
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    // TEST RELATED
    Route::get('camera/test/{camera}', [CameraController::class, 'test']);

    // MASTER DATA
    Route::resource('user', UserController::class)->except(['create', 'edit']);
    Route::resource('accessGate', AccessGateController::class)->except(['create', 'edit']);
    Route::resource('camera', CameraController::class)->except(['create', 'edit']);

    // ACCESS LOG RELATED
    Route::get('accessLogs', [AccessLogController::class, 'index']);
    Route::delete('accessLogs', [AccessLogController::class, 'destroy']);
    Route::get('accessLogs/export', [AccessLogController::class, 'export']);

    // MEMBER RELATED
    Route::get('member/export', [MemberController::class, 'export']);
    Route::post('member/import', [MemberController::class, 'import']);
    Route::delete('member/deleteAll', [MemberController::class, 'deleteAll']);
    Route::resource('member', MemberController::class)->except(['create', 'edit']);

    Route::post('snapshot/delete', [SnapshotController::class, 'destroy']);
    Route::get('snapshot', [SnapshotController::class, 'index']);
});
