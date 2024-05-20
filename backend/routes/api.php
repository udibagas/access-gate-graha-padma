<?php

use App\Http\Controllers\AccessGateController;
use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
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
    Route::get('accessGate', [AccessGateController::class, 'index']);

    // MASTER DATA
    Route::middleware('admin')->group(function () {
        Route::get('camera/test/{camera}', [CameraController::class, 'test']);
        Route::apiResource('accessGate', AccessGateController::class, ['except' => 'index']);

        Route::apiResources([
            'user' => UserController::class,
            'camera' => CameraController::class
        ]);

        Route::delete('accessLogs', [AccessLogController::class, 'destroy']);
        Route::delete('member/deleteAll', [MemberController::class, 'deleteAll']);
        Route::post('snapshot/delete', [SnapshotController::class, 'destroy']);

        Route::prefix('backup')->group(function () {
            Route::get('', [BackupController::class, 'index']);
            Route::post('', [BackupController::class, 'store']);
            Route::delete('', [BackupController::class, 'destroy']);
            Route::put('', [BackupController::class, 'restore']);
        });
    });

    // ACCESS LOG RELATED
    Route::get('accessLogs', [AccessLogController::class, 'index']);
    Route::get('accessLogs/export', [AccessLogController::class, 'index']);

    // MEMBER RELATED
    Route::get('member/export', [MemberController::class, 'index']);
    Route::post('member/import', [MemberController::class, 'import']);
    Route::apiResource('member', MemberController::class);

    Route::get('snapshot', [SnapshotController::class, 'index']);
    Route::get('snapshot/download', [SnapshotController::class, 'download']);
});
